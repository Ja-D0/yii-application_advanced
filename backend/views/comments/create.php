<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Comments $model */

$this->title = 'Comments';
?>
<div class="comments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
