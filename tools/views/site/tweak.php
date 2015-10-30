<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Tweak LOVD Tools';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-tweak">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2> **** WARNING **** </h2>
    <h4>Since this tool works directly on your LOVD installation you can mess thing seriously up if you dont't know what your are doing!! </h4>
    <h4>We do NOT give any warranty. </h4>
    <h5>If you are not scared wou can tweak this tool to make it work on your installation.</h5>

    <p>

        This tool can work directly on your LOVD installation but before you can do that you have to tweak the database connections:
        Folder 'config' contains the file db.php this file contains the database login data.
        For every database connnection you have to make an bd file. The second one you can can db2.php and db3.php for the third one.
        <pre>
            /**
             * For remote databases: On the console, forward local port 3307 to remote port 3306
             * ssh "user"@"host" -L 3307:127.0.0.1:3306 -N
             * host: '127.0.0.1'
             * port: 3307
             **/
            return [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;port=3307;dbname=databasename',
                'username' => 'username',
                'password' => 'password',
                'charset' => 'utf8',
            ];
        </pre>

        The config folder also contains a file web.php. Every db.php file has to be declaired in the web.php file.
        So Yii knows which databases are available.
        <pre>
            example"
            'components' => [
                ...
                'db' => require(__DIR__ . '/db.php'),
                //'db2' => require(__DIR__ . '/db2.php'), // Uncomment when you use more than one database.
                //'db3' => require(__DIR__ . '/db3.php'), // Uncomment when you use more than one database.
                //'db4' => require(__DIR__ . '/db4.php'), // Uncomment when you use more than one database.
                //'db5' => require(__DIR__ . '/db5.php'), // Uncomment when you use more than one database.
            ],
        </pre>

        The last file you have to change is the file LovdConnection.php in the folder models.
        The dropdown menu to select which database you want to use is hardcoded!
        The top of the file looks like this:
        <pre>
            namespace app\models;

            use Yii;
            use yii\base\Model;
            use yii\helpers\ArrayHelper;
            //use yii\db\Query;

            /**
             * LoginLovdForm is the model behind the login lovd form.
             */
            class LovdConnection extends Model
            {
                public $databases = array('db' => 'Yii2 basic',
                                          'db2' => 'LOVD local',
                                          'db3' => 'local infor schema',
                                          'db4' => 'LOVD shared');
                public $database;
                public $database_name;
                public $information_schema;
                private $_lovdDatabaseConnection;

        </pre>

        Change the databases array. The keys are refering to keys you made in the web.php file.
        The value is any name you wish.
    </p>
</div>
