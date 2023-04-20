<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Ads $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ads-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlenght' => 256, 'rows' => 6]) ?>

    <?= $form->field($model, 'category')->dropDownList(\yii\helpers\ArrayHelper::map($category, 'id', 'title')) ?>

    <p>

    </p>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
