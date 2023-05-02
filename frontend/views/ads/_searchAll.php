<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \frontend\models\AdsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ads-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ads'],
        'method' => 'get',
    ]); ?>



    <?= $form->field($model, 'search_string') ?>
    <p>

    </p>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить фильтры',['ads'], ['class' => 'btn btn-primary'])?>

        <?= Html::resetButton('Не работает(спросить)', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
