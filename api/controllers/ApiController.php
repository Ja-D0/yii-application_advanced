<?php

namespace api\controllers;


use common\models\Ads;
use common\models\User;
use yii\web\NotFoundHttpException;

class ApiController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionGet_users()
    {
        $models = User::find()->all();
        foreach ($models as $model) {
            foreach (["password_hash", "auth_key", "password_reset_token", "verification_token"] as $offset)
                $model->offsetUnset($offset);
        }
        if ($models == null) {
            throw new NotFoundHttpException('Пользователи не найдены');
        }
        return $models;
    }

    public function actionGet_user($id)
    {
        $model = User::findIdentity($id);
        foreach (["password_hash", "auth_key", "password_reset_token", "verification_token"] as $offset)
            $model->offsetUnset($offset);
        if ($model == null) {
            throw new NotFoundHttpException('Пользователь не найден');
        }
        return $model;
    }

    public function actionGet_posts()
    {
        $posts = Ads::find()->all();
        foreach ($posts as $post) {
            $post->category = $post->category_name->title;
        }
        if ($posts == null) {
            throw new NotFoundHttpException('Посты не найдены');
        }
        return $posts;
    }

    public function actionGet_post($id)
    {
        $post = Ads::findOne($id);
        $post->category = $post->category_name->title;

        if ($post == null) {
            throw new NotFoundHttpException('Пост не найден');
        }
        return $post;
    }
}
