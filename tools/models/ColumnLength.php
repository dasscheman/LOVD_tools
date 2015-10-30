<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * LoginLovdForm is the model behind the login lovd form.
 */
class ColumnLength extends LovdConnection
{
    public $table;
    public $columns;
    public $diffLength = 20;
    public $margin = 10;
    public $select;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['host', 'database', 'username', 'password'], 'required'],
            // rememberMe must be a boolean value
            [['rememberMe', 'select'], 'boolean'],
	    [['table','columns'], 'string'],
	    //['columns', 'array'],
	    [['diffLength', 'margin'], 'integer'],
        ];
    }

    /**
     * set the table
     **/
    public function setTable($table)
    {
	$this->table = $table;
    }

    /**
     * set the columns
     **/
    public function setColumns($columns)
    {
	$this->columns = $columns;
    }

    /**
     * set the difLength
     **/
    public function setDiffLength($diffLength)
    {
	$this->diffLength = $diffLength;
    }

    /**
     * set the margin
     **/
    public function setmargin($margin)
    {
	$this->margin = $margin;
    }

    /**
     * get the columns
     * @returns columns for $this->table
     **/
    public function getTables()
    {
	$columns[] = 'name';
        $this->connectInformationDb();
        $query = $this->createCommand("SELECT TABLE_NAME name
				       FROM TABLES
				       WHERE TABLE_SCHEMA = '" . $this->getDatabaseName() . "'");
	$columns = $query->queryAll();
	$data = ArrayHelper::map($columns, 'name', 'name');
        $this->disconnect();
        return $data;
    }

    /**
     * get the columns
     * @returns columns for $this->table
     **/
    public function getColumns()
    {
        $this->connect();
        $query = $this->createCommand('SHOW COLUMNS FROM ' . $this->table);
        $data = $query->queryColumn();
        $this->disconnect();
        return $data;
    }

    /**
     * Alter the columns of $this->column
     * @returns nothing
     **/
    public function alterColumns()
    {
        $this->connect();
        $reservedLength = $this->getReservedColumnLength();
        $maxLength = $this->getMaxFieldLengthPerColumn();
        $diffData = $this->getDifColumnLength($reservedLength, $maxLength);

        foreach ($diffData as $key=>$record){
            $type = 'varchar(' . $record['new_length'] . ')';
            $column = $key;
            $query = $this->alterColumn($column, $type);
            $query->query();
        }

        $this->disconnect();
    }

    /**
     * get the columnlength
     * @returns the length of the columns for $this->table
     **/
    public function getMaxFieldLengthPerColumn()
    {
	$data = '';
	$this->connect();
        foreach ($this->columns as $column) {
	    $query = $this->createCommand('SELECT MAX(LENGTH(`' . $column . '`))
					FROM ' . $this->table);
	    $data[$column] = $query->queryColumn()[0];
	}
	$this->disconnect();
        return $data;
    }

    /**
     * get the maxcolumnlength with a minum length of 20 characters
     * for columns with DATA_TYPE varchar.
     * @returns the max length of the columns for $this->table
     **/
    public function getReservedColumnLength()
    {
	$db_name = $this->getDatabaseName();

        $this->connectInformationDb();
	$data = '';
	foreach ($this->columns as $column) {
	    $query = $this->createCommand(
		"SELECT CHARACTER_MAXIMUM_LENGTH
		FROM COLUMNS
		WHERE TABLE_NAME LIKE '" . $this->table . "'
		AND DATA_TYPE = 'varchar'
		AND TABLE_SCHEMA ='" . $db_name . "'
		AND COLUMN_NAME = '" . $column . "'
		AND CHARACTER_MAXIMUM_LENGTH > '10'
		LIMIT 0 , 1");
	    if (isset($query->queryColumn()[0])) {
		$data[$column] = $query->queryColumn()[0];
	    }
	}
        $this->disconnectInformationDb();
        return $data;
    }

    /**
     * get the table info from the information schema
     * @returns the table information for $this->table
     **/
    public function getTableInfo()
    {
	$db_name = $this->getDatabaseName();
        $this->connectInformationDb();
	$data = '';
	$query = $this->createCommand(
		"SELECT DATA_LENGTH AS data_length,
		    MAX_DATA_LENGTH AS max_data_length,
		    DATA_FREE AS data_free,
		    AVG_ROW_LENGTH AS average_row_length
		FROM TABLES
		WHERE TABLE_NAME LIKE '" . $this->table . "'
		AND TABLE_SCHEMA ='" . $db_name . "'
		LIMIT 0 , 1");
	if (isset($query->queryAll()[0])) {
	    $data[$this->table] = $query->queryOne();
	}
        $data[$this->table]['calculated'] = $this->getTableSizeInfo()['SIZE'];

        $this->disconnectInformationDb();
	$provider = new ArrayDataProvider([
	    'allModels' => $data,
	    'pagination' => [
		'pageSize' => 50,
	    ],
	]);
	return $provider;
    }

    public function getTableSizeInfo()
    {
	$db_name = $this->getDatabaseName();
        $this->connectInformationDb();
	$data = '';
	$query = $this->createCommand(
	    "SELECT
		SUM(DATA_TYPE)+
		SUM(DATA_TYPE IN ('varchar', 'text', 'decimal'))+
		SUM(DATA_TYPE IN ('text'))*12+
		SUM(DATA_TYPE IN ('tinyint'))+
		SUM(DATA_TYPE IN ('smallint'))*2+
		SUM(DATA_TYPE IN ('mediumint'))*3+
		SUM(DATA_TYPE IN ('int'))*4+
		SUM(DATA_TYPE IN ('datetime'))*8+
		SUM(DATA_TYPE IN ('varchar'))+
		SUM(CHARACTER_OCTET_LENGTH)+
		SUM(DATA_TYPE IN ('decimal'))
	    AS SIZE
	    FROM COLUMNS
	    WHERE TABLE_SCHEMA = '" . $db_name . "'
	    AND TABLE_NAME  = '" . $this->table . "'");

	if (isset($query->queryAll()[0])) {
	    $data = $query->queryOne();
	}

        $this->disconnectInformationDb();

	return $data;
    }

    /**
     * get the get the difference between max and reserved columns length
     * @param array() $reservedFieldLength Array with column names and reserved column length
     * @param array() $maxFieldLength Array with column names and the max column length of any field in that column
     * @param int $difgLength integer with the minimum in difference between $length and $maxLength
     * @returns the difference in length between $length and $maxLength
     **/
    public function getDifColumnLength($reservedFieldLength, $maxFieldLength)
    {
	$data = array();
        foreach ($reservedFieldLength as $key=>$value) {
	    $diff = $value - $maxFieldLength[$key];

	    $new_length = $maxFieldLength[$key] + $this->margin;
	    if ($new_length < $maxFieldLength[$key]){
		// The new length my never by smaller than the current max lenght,
		// because than we are going to delete data!!
		continue;
	    }

	    $saved_length = $value - $new_length;
	    if (abs($saved_length) < $this->diffLength){
		// If the difference is to small were are not interested to change is.
		continue;
	    }

		$data[$key] =
		    [
			'column_name'=>$key,
			'reserved'=>$value,
			'max'=>$maxFieldLength[$key],
			'dif'=>$diff,
			'new_length'=>$new_length,
			'saved_length'=>$saved_length,
		    ];

	}

	return $data;
    }

    public function getDataProviderFormat($data)
    {
	$provider = array();
	$provider = new ArrayDataProvider([
	    'allModels' => $data,
	    'pagination' => [
		'pageSize' => 50,
	    ],
	    'sort' => [
		'attributes' => ['column_name', 'reserved', 'max', 'dif', 'new_length', 'saved_length'],
	    ],
	]);
	return $provider;
    }
}
