<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Ads $model */

$this->title = 'Создать объявление';
$this->params['breadcrumbs'][] = ['label' => 'Мои объявления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
    ]) ?>

</div>
