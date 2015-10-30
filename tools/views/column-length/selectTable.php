<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LovdConnectForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Select table';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="column_length-select-table">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Select one of the tables on which you want to apply a varchar analysis:</p>

<?php $form = ActiveForm::begin([
        'id' => 'column-length-select-table-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?php
            echo $form->field($model, 'table')->dropDownList($model->getTables(),
							    ['prompt'=>'Select table']);
        ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Select', ['class' => 'btn btn-primary', 'name' => 'select-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
