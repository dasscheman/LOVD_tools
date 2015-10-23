<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginLovdForm is the model behind the login lovd form.
 */
class LovdConnectionForm extends Model
{
    public $host;
    public $database;
    public $username;
    public $password;
    public $rememberMe = false;
    public $isAvailable = false;

    private $_lovdDatabaseConnection;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['host', 'database', 'username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Connects to the LOVD database.
     * @return boolean whether connected successfully.
     */
    public function connect()
    {
        $this->_lovdDatabaseConnection = new \yii\db\Connection([
            'dsn' => 'mysql:host=' . $this->host . ';dbname=' . $this->database,
            'username' => $this->username,
            'password' => $this->password,
            'charset' => 'utf8',
        ]);
        $this->_lovdDatabaseConnection->open();
        Yii::$app->lovdConnection->host =  $this->host;
        Yii::$app->lovdConnection->database =  $this->database;
        Yii::$app->lovdConnection->username =  $this->username;
        Yii::$app->lovdConnection->password =  $this->password;
        Yii::$app->lovdConnection->isAvailable =  true;
    }

    /**
     * Disonnects from the LOVD database.
     * @return boolean whether disconnected successfully.
     */
    public function disconnect()
    {
        $this->_lovdDatabaseConnection->close();
        Yii::$app->lovdConnection->host =  '';
        Yii::$app->lovdConnection->database =  '';
        Yii::$app->lovdConnection->username =  '';
        Yii::$app->lovdConnection->password =  '';
        Yii::$app->lovdConnection->isAvailable =  false;
    }
}
