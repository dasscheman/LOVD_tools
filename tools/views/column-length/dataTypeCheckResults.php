<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LovdConnect */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = 'Varchar datatype';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lovd_phenotype-check-data-type">
    <h1><?= Html::encode($this->title) ?></h1>

    <p></p>
    <?= GridView::widget(
        [
            'dataProvider' => $diffData,
            //'filterModel' => $searchModel,
            'columns' =>
                [
                    'id',
                    'mysql_type',
                    'Information_schema',
                ],
        ]);
    ?>

</div>
