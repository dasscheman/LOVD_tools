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

    <?php $form = ActiveForm::begin([
        'id' => 'analys-length-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>
        <div class="form-group">
            <div class="col-lg-4">
                <h2>Settings</h2>
                <h4>WARNING</h4>
                <p>Changing the order wil default the settiing!</p>

                <p>Changes smaller than <b>'Diff length'</b> are not displayed and can not be modified. <br>
                The <b>'Margin'</b> is how much characters should be added to the largest field of a column.</p>
                <?= $form->field($model, 'diffLength') ?>
                <?= $form->field($model, 'margin') ?>
                <?= Html::submitButton('Submit settings', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>

            </div>
        </div>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget(
        [
            'dataProvider' => $tableInfo,
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
    <?=Html::beginForm([
        '/column-length/alter-columns',
        'id' => 'analys-length-form',
        'model' => $model,
    ]);?>
    <?=Html::submitButton('Alter Column length', ['class' => 'btn btn-primary', 'name' => 'submit-button' ,]);?>
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
                        'new_length',
                        'saved_length',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
            ]);
        ?>
    <?= Html::endForm();?>

</div>
