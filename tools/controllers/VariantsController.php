<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Variants;
use app\models\LovdConnection;


class VariantsController extends Controller
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

    public function actionViewGenomicVariants()
    {
        // TODO Should use SESSION to store data temporaly.
        $request = Yii::$app->request;
        $model = new Variants;
        if (empty($request->get('dbmodel')['database_id'])) {
            return $this->render('/lovd-connection/connect', [
               // 'dbmodel' => $dbmodel,
            ]);
        }

        $model->setDatabaseId($request->get('dbmodel')['database_id']);

        //Put all data together.
        $data = $model->getGenomicVariants();

        // Fit the data with arrayDataProvider so the gridview understands the format.
        $data = $model->getDataProviderFormat($data);
        $data->sort = [
            'attributes' => ['id', 'effectid', 'chromosome', 'VariantOnGenome/DNA'],
        ];
	    
        return $this->render('viewGenomicVariants', [
            'data' => $data,
        ]);
    }
}
