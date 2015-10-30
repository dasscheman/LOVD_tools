<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ColumnLength;
use app\models\LovdConnection;


class ColumnLengthController extends Controller
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

    public function actionAnalysLength()
    {
        $request = Yii::$app->request;
        $model = new ColumnLength;
        if (empty($request->get('dbmodel')['database_id'])) {
            return $this->render('/lovd-connection/connect', [
                'dbmodel' => $dbmodel,
            ]);
        }
        $model->database_id = $request->get('dbmodel')['database_id'];
        $model->load($request->post('model'));

        if (! empty($request->post('ColumnLength')['diffLength'])) {
            $model->setDiffLength($request->post('ColumnLength')['diffLength']);
        }

        /*
        if (empty(Yii::$app->request->post('ColumnLength')['table'])) {
            return $this->render('selectTable', [
                'model' => $model,
            ]);
        }*/

        // TEMP
        $model->setTable('lovd_v3_phenotypes');
        $model->setColumns($model->getColumns());
        $reservedLength = $model->getReservedColumnLength();
        $maxLength = $model->getMaxFieldLengthPerColumn();
        $diffData = $model->getDifColumnLength($reservedLength, $maxLength);
        $tableInfo = $model->getTableInfo();
        return $this->render('analysLength', [
            'model' => $model,
            'diffData' => $diffData,
            'tableInfo' => $tableInfo,
        ]);
    }
}
