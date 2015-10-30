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
                    'calculated',
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
                    'calculated',
                ],
        ]);

    ?>

</div>
