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
	    ['diffLength', 'integer'],
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
		    DATA_FREE AS data_free
		FROM TABLES
		WHERE TABLE_NAME LIKE '" . $this->table . "'
		AND TABLE_SCHEMA ='" . $db_name . "'
		LIMIT 0 , 1");
	if (isset($query->queryAll()[0])) {
	    $data[$this->table] = $query->queryAll()[0];
	}

        $this->disconnectInformationDb();
	$provider = new ArrayDataProvider([
	    'allModels' => $data,
	    'pagination' => [
		'pageSize' => 50,
	    ],
	    /*'sort' => [
		'attributes' => ['data_length', 'max_data_length', 'data_free'],
	    ],*/
	]);
	return $provider;
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
	$data = '';
        foreach ($reservedFieldLength as $key=>$value) {
	    $diff = $value - $maxFieldLength[$key];
	    if ($diff > $this->diffLength){
		$data[$key] =
		    [
			'column_name'=>$key,
			'reserved'=>$value,
			'max'=>$maxFieldLength[$key],
			'dif'=>$diff,
		    ];
	    }
	}

	$provider = new ArrayDataProvider([
	    'allModels' => $data,
	    'pagination' => [
		'pageSize' => 50,
	    ],
	    'sort' => [
		'attributes' => ['column_name', 'reserved', 'max', 'dif'],
	    ],
	]);
	return $provider;
    }
}
