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
        // TODO Should use SESSION to store data temporaly.
        $request = Yii::$app->request;
        $model = new ColumnLength;
        if (empty($request->get('dbmodel')['database_id'])) {
            return $this->render('/lovd-connection/connect', [
               // 'dbmodel' => $dbmodel,
            ]);
        }

        $model->setDatabaseId($request->get('dbmodel')['database_id']);
        if ( isset($request->post('ColumnLength')['diffLength'])) {
            $model->setDiffLength($request->post('ColumnLength')['diffLength']);
        }
        if ( isset($request->post('ColumnLength')['margin'])) {
            $model->setMargin($request->post('ColumnLength')['margin']);
        }

        /*
        if (empty(Yii::$app->request->post('ColumnLength')['table'])) {
            return $this->render('selectTable', [
                'model' => $model,
            ]);
        }*/

        // TEMP
        //$model->setTable('lovd_v3_phenotypes');
        $model->setColumns($model->getColumns());
        $reservedLength = $model->getReservedColumnLength();
        $maxLength = $model->getMaxFieldLengthPerColumn();

        //Put all data together.
        $diff = $model->getDifColumnLength($reservedLength, $maxLength);

        // Fit the data with arrayDataProvider so the gridview understands the format.
        $diffData = $model->getDataProviderFormat($diff);
        $diffData->sort = [
            'attributes' => ['column_name', 'form_type', 'reserved', 'max', 'dif', 'new_length', 'saved_length'],
        ];

        // Now get the summary data for all columns
        $tabledata = $model->getTableInfo();
        $sum = 0;
        foreach ($diff as $item) {
            $sum += $item['saved_length'];
        }
        $tabledata[$model->table]['total_varchar_saved'] = $sum;
        $tableInfo = $model->getDataProviderFormat($tabledata);

        return $this->render('analysLength', [
            'model' => $model,
            'diffData' => $diffData,
            'tableInfo' => $tableInfo,
        ]);
    }

    public function actionAlterColumns()
    {
        $request = Yii::$app->request;
        //$model = $request->get('model');
        $selection = $request->post('selection');
        if (! isset($selection)) {
            $session = Yii::$app->session;
            $session->setFlash('warning', 'You did not select any column to alter.');
            return $this->goBack();
        }

        $model = new ColumnLength;

        if (! empty($request->get('model')['diffLength'])) {
            $model->setDiffLength($request->get('model')['diffLength']);
        }
        if (! empty($request->get('model')['margin'])) {
            $model->setMargin($request->get('model')['margin']);
        }

        $model->setDatabaseId($request->get('model')['database_id']);
        //$model->setTable('lovd_v3_phenotypes');
        $model->setColumns($selection);

        $oldTableInfo = $model->getTableInfo();
        $oldTableInfo = $model->getDataProviderFormat($oldTableInfo);

        $model->alterColumns();

        $newTableInfo = $model->getTableInfo();
        $newTableInfo = $model->getDataProviderFormat($newTableInfo);

        return $this->render('analysResults', [
            'model' => $model,
            'oldTableInfo' => $oldTableInfo,
            'newTableInfo' => $newTableInfo,
        ]);
    }

    public function actionDataTypeCheck()
    {
        // TODO Should use SESSION to store data temporaly.
        $request = Yii::$app->request;
        $model = new ColumnLength;
        if (empty($request->get('dbmodel')['database_id'])) {
            return $this->render('/lovd-connection/connect', [
               // 'dbmodel' => $dbmodel,
            ]);
        }

        $model->setDatabaseId($request->get('dbmodel')['database_id']);
        if ( isset($request->post('ColumnLength')['diffLength'])) {
            $model->setDiffLength($request->post('ColumnLength')['diffLength']);
        }
        if ( isset($request->post('ColumnLength')['margin'])) {
            $model->setMargin($request->post('ColumnLength')['margin']);
        }

        /*
        if (empty(Yii::$app->request->post('ColumnLength')['table'])) {
            return $this->render('selectTable', [
                'model' => $model,
            ]);
        }*/

        // TEMP
        $model->setColumns($model->getColumns());
        $reservedLength = $model->getReservedColumnLength();

        //Put all data together.
        $diff = $model->getColumnProperty($reservedLength);

        // Fit the data with arrayDataProvider so the gridview understands the format.
        $diffData = $model->getDataProviderFormat($diff);
        /*$diffData->sort = [
            'attributes' => ['column_name', 'reserved', 'max', 'dif', 'new_length', 'saved_length'],
        ];*/

        return $this->render('dataTypeCheckResults', [
            'diffData' => $diffData,
        ]);
    }
}
