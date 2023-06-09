<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Ads $model */

$this->title = $model->title;
if (strpos(Yii::$app->request->referrer, 'index')) {
    $param = ['label' => 'Мои объявления', 'url' => ['index']];
}
else{
    $param = ['label' => 'Все объявления', 'url' => ['ads']];
}
$this->params['breadcrumbs'][] = $param;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ads-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'author',
            'description',
            'category_name.title',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?= $this->render('../comments/create' , ['model' => $comment , 'ads' => $model->id])?>
    <?= $this->render('../comments/index', ['dataProvider' => $comments , 'view' => $model->id, 'searchModel' => new \backend\models\CommentsSearch()]) ?>


</div>
