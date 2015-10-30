<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php
    if (Yii::$app->session->hasFlash('warning')) {
        echo Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => Yii::$app->session->getFlash('warning'),
        ]);
    }?>

    <div class="jumbotron">
        <h1>LOVD Tools!</h1>

        <p class="lead">This is an unsupported lovd tool. The aim is to group several different scripts in 1 tool.
        You are free to use this. But using any of this tool is on your own responsability.
        This tool requires some coding to get it working for your lovd installation</p>

        <p><a class="btn btn-lg btn-success" href="http://www.lovd.nl">Go to www.lovd.nl</a></p>
        <p> <?php echo Html::a('Howto tweak LOVD tools &raquo',
                                [ '/site/tweak' ],
                                [ 'class' => 'btn btn-primary', ]); ?> </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Analyse varchar fields</h2>

                <p>Here we analyse the varchar fields of table phenotypes and change the reserved length when necessary.</p>
                <?php
                if (isset($dbmodel) && ! empty($dbmodel->database_id)) {
                echo Html::a('Analyse &raquo',
                                [
                                    '/column-length/analys-length',
                                    'dbmodel' => $dbmodel,
                                ],
                                [
                                    'class' => 'btn btn-primary',
                                ]
                            );
                }
                ?>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
