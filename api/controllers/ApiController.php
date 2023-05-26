<?php

namespace api\controllers;


use common\models\Ads;
use common\models\User;
use yii\web\HttpException;
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

    public function actionPosts()
    {
        $ads = Ads::find()->all();
        if($ads == null){
            throw new NotFoundHttpException('Posts not found');
        }
        $postList = array();
        foreach ($ads as $post){
            $post->category = $post->category_name->title;
            $postList[] = $post;
        }
        return $postList;
    }

    public function actionPost($id)
    {
        $post = Ads::findOne(['id' => $id]);
        if ($post == null){
            throw new NotFoundHttpException('Post not found');
        }
        $post->category = $post->category_name->title;
        $postList[] = $post;
        return $postList;
    }
}
