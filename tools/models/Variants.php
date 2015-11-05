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
class Variants extends LovdConnection
{
    public $table = 'lovd_v3_variants';

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
     * Alter the columns of $this->column
     * @returns nothing
     **/
    public function getGenomicVariants()
    {
        $this->connect();

	$data = '';
	$query = $this->createCommand(
	    "SELECT id,
	    effectid,
	    chromosome,
	    `VariantOnGenome/DNA`
	    FROM " . $this->table);
	//    WHERE TABLE_SCHEMA = '" . $db_name . "'
	//    AND DATA_TYPE = 'decimal'
	//    AND TABLE_NAME  = '" . $this->table . "'");

	if (isset($query->queryAll()[0])) {
	    $data = $query->queryAll();
	}
        $this->disconnect();

	return $data;
    }



    public function getDataProviderFormat($data)
    {
	$provider = array();
	$provider = new ArrayDataProvider([
	    'allModels' => $data,
	    'pagination' => [
		'pageSize' => 100,
	    ],
	]);
	return $provider;
    }
}
