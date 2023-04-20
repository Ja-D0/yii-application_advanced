<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Ads $model */

$this->title = 'Изменить объявление: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои объявления', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="ads-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category
    ]) ?>

</div>
