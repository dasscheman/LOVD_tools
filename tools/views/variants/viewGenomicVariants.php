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
<?php //var_dump($data); exit; ?>
    <?php $form = ActiveForm::begin([
        'id' => 'analys-length-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

    <?= GridView::widget(
        [
            'dataProvider' => $data,
            //'filterModel' => $searchModel,
            'columns' =>
                [
                    'id',
                    'effectid',
                    'chromosome',
                    'VariantOnGenome/DNA',
                    /*[
                        'label'=>'Calculated Total Size (bites)',
                        'value' =>'calculated_total_size'
                    ],*/
                ],
        ]);

    ?>
</div>
