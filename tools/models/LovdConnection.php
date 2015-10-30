<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\db\QueryBuilder;

/**
 * LoginLovdForm is the model behind the login lovd form.
 */
class LovdConnection extends Model
{
    public $databases = array('db' => 'Yii2 basic',
                              'db2' => 'LOVD local',
                              //'db3' => 'LOVD shared',
                              );
    public $database_id;
    private $_lovdDatabaseConnection;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // database is both required
            [['database_id'], 'required'],
            [['database_id'], 'string'],
        ];
    }

    /**
     * Sets the database ID
     */
    public function setDatabaseId($database_id)
    {
        $this->database_id = $database_id;
    }

    /**
     * Disonnects from the LOVD database.
     * @return boolean whether disconnected successfully.
     */
    public function deselectDatabaseId()
    {
        $this->_lovdDatabaseConnection->close();
        $this->database = null;
        return true;
    }

    /**
     * Connects to the LOVD database.
     * @return boolean whether connected successfully.
     */
    public function connect()
    {
        $db = Yii::$app->get($this->database_id);
        $this->_lovdDatabaseConnection = new \yii\db\Connection([
            'dsn' => $db->dsn,
            'username' => $db->username,
            'password' => $db->password,
            'charset' => $db->charset,
        ]);
        $this->_lovdDatabaseConnection->open();

        // TODO: nette afhandeling door $this->_lovdDatabaseConnection->open(); af te vangen.
        return true;
    }

    /**
     * Disconnects to the LOVD database.
     * @return boolean whether connected successfully.
     */
    public function disconnect()
    {
        $this->_lovdDatabaseConnection->close();
        return true;
    }

    /**
     * Connects to the Infromation databbase schem of the selected LOVD database.
     * @return boolean whether connected successfully.
     */
    public function connectInformationDb()
    {
        $db = Yii::$app->get($this->database_id);
        $tempDsn = preg_replace("/dbname=.*/", "dbname=information_schema", $db->dsn);

        $this->_lovdDatabaseConnection = new \yii\db\Connection([
            'dsn' => $tempDsn,
            'username' => $db->username,
            'password' => $db->password,
            'charset' => $db->charset,
        ]);
        $this->_lovdDatabaseConnection->open();

        // TODO: nette afhandeling door $this->_lovdDatabaseConnection->open(); af te vangen.
        return true;
    }

    /**
     * Disonnects from the Infromation databbase schem of the selected LOVD database.
     * @return boolean whether connected successfully.
     */
    public function disconnectInformationDb()
    {
        $this->_lovdDatabaseConnection->close();
        return true;
    }

    /**
     * CreateCommand for currecnt db connection
     * @return sql statement.
     */
    public function createCommand($query)
    {
        return $this->_lovdDatabaseConnection->createCommand($query);
    }

    /**
     * Alter column for currecnt db connection
     * @return sql statement.
     */
    public function alterColumn($column, $type)
    {
        return $this->_lovdDatabaseConnection->createCommand()->alterColumn( $this->table, $column, $type);
    }

    /**
     * Connects to the LOVD database.
     * @return boolean whether connected successfully.
     */
    public function getDatabaseName()
    {
        $db = Yii::$app->get($this->database_id);
        return preg_replace("/.*dbname=/", "", $db->dsn);
    }
}
