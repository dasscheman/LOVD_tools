<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LovdConnect */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = 'Varchar length analyser';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lovd_phenotype-get-length">
    <h1><?= Html::encode($this->title) ?></h1>

    <p></p>

    <?= GridView::widget(
        [
            'dataProvider' => $oldTableInfo,
            //'filterModel' => $searchModel,
            'columns' =>
                [
                    'data_length',
                    'max_data_length',
                    'data_free',
                    'average_row_length',
                    [
                        'label'=>'Calculated Total Size (bites)',
                        'value' =>'calculated_total_size'
                    ],
                    [
                        'label'=>'Calculated Varchar Size (bites)',
                        'value' =>'calculated_varchar_size'
                    ],
                    [
                        'label'=>'Calculated Varchar Size (char)',
                        'value' =>'calculated_varchar_length'
                    ],
                ],
        ]);
    ?>
    <?= GridView::widget(
        [
            'dataProvider' => $newTableInfo,
            //'filterModel' => $searchModel,
            'columns' =>
                [
                    'data_length',
                    'max_data_length',
                    'data_free',
                    'average_row_length',
                    [
                        'label'=>'Calculated Total Size (bites)',
                        'value' =>'calculated_total_size'
                    ],
                    [
                        'label'=>'Calculated Varchar Size (bites)',
                        'value' =>'calculated_varchar_size'
                    ],
                    [
                        'label'=>'Calculated Varchar Size (char)',
                        'value' =>'calculated_varchar_length'
                    ],
                ],
        ]);

    ?>

</div>
