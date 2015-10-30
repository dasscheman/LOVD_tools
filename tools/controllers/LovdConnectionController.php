<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\LovdConnection;

class LovdConnectionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSelectDatabase()
    {
        $dbmodel = new LovdConnection();

        if ($dbmodel->load(Yii::$app->request->post()) && isset($dbmodel->database_id)) {
            return $this->render('/site/index', [
                'dbmodel' => $dbmodel,
            ]);
        }

        return $this->render('connect', [
            'dbmodel' => $dbmodel,
        ]);
    }

    public function actionDeselect()
    {

    }
}
