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

    <p>Please fill out the following fields to connect to a LOVD database:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'analys-length-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'diffLength') ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
        </div>

        <?= GridView::widget(
            [
                'dataProvider' => $tableInfo,
                //'filterModel' => $searchModel,
                'columns' =>
                    [
                        'data_length',
                        'max_data_length',
                        'data_free',
                    ],
            ]);

        ?>
        <?= GridView::widget(
            [
                'dataProvider' => $diffData,
                //'filterModel' => $searchModel,
                'columns' =>
                    [
                        [ 'class' => 'yii\grid\SerialColumn' ],
                        [ 'class' => 'yii\grid\CheckboxColumn', ],
                        'column_name',
                        'reserved',
                        'max',
                        'dif',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
            ]);

        ?>

    <?php ActiveForm::end(); ?>
</div>
