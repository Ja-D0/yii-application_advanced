<?php

namespace api\controllers;


use common\models\Ads;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use function MongoDB\BSON\fromJSON;
use function MongoDB\BSON\toJSON;

class ApiController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return array_merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'create_post' => ['POST'],
                        'update_post' => ['POST'],
                        'check_user' => ['POST']
                    ],
                ],
            ]
        );
    }

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


    /*
     * Для java приложения
     */
    public function actionCreate_post()
    {
        $post = new Ads();
        $post->title = $this->request->post("title");
        $post->description = $this->request->post("description");
        $post->category = 3;
        $post->author = $this->request->post("author");
        if (!$post->save())
            throw new BadRequestHttpException();
        return ['message' => "OK", "status_code" => 200];
    }

    public function actionUpdate_post()
    {
        $post = Ads::find()->where(["created_at" => $this->request->post("created_at")])->one();
        if ($this->request->isPost) {
            $post->title = $this->request->post("title");
            $post->description = $this->request->post("description");
            $post->category = 3;
            $post->author = $this->request->post("author");
            if (!$post->save())
                throw new BadRequestHttpException();
        }
        return ['message' => "OK", "status_code" => 200];
    }

    public function actionDelete_post()
    {
        $post = Ads::find()->where(["created_at" => $this->request->post("created_at")])->one();
        if ($post->delete()) {
            return ['message' => "OK", "status_code" => 200];
        }
        throw new BadRequestHttpException();

    }

    public function actionCheck_user()
    {
        if ($this->request->isPost) {
            $user = User::findByUsername($this->request->post("username"));
            foreach (["password_hash", "auth_key", "password_reset_token", "verification_token"] as $offset)
                $user->offsetUnset($offset);
            if ($user == null) {
                throw new NotFoundHttpException('Пользователь не найден');
            }
        }
        return $user;
    }
}
