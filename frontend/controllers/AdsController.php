<?php

namespace frontend\controllers;

use common\models\Ads;
use common\models\Category;
use common\models\Comments;
use common\models\User;
use frontend\models\AdsSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * AdsController implements the CRUD actions for Ads model.
 */
class AdsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'update', 'create', 'delete', 'ads'],
                            'allow' => true,
                            //'roles' => ['@'],
                            //'matchCallback' => function ($rule, $action) {
                            //    $user = Yii::$app->user->identity;
                            //    return in_array($user->status, User::STATUS);
                            //}
                            'roles' => ['user', 'admin']
                        ],

                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Ads models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdsSearch();
        $dataProvider = $searchModel->search($this->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAds()
    {
        $searhModel = new AdsSearch();
        $dataProvider = $searhModel->searchAll($this->request->get());

        return $this->render('Ads', ['dataProvider' => $dataProvider,
            'searchModel' => $searhModel]);
    }


    /**
     * Displays a single Ads model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $comment = new Comments();

        if ($this->request->isPost) {
            if ($comment->load($this->request->post())) {
                $comment->author = Yii::$app->user->identity->id;
                $comment->ads = $id;
                $comment->save();
            }
        }

        if ($this->request->get())
            return $this->render('view', [
                'model' => $this->findModel($id),
                'comment' => $comment,
                'comments' => new ActiveDataProvider(['query' => Comments::find()->where(['ads' => $id]),
                    'sort' => ['defaultOrder' => [
                        'created_at' => SORT_DESC,
                    ]
                    ]])
            ]);

        return null;
    }

    /**
     * Creates a new Ads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ads();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->author = Yii::$app->user->identity->nickname;
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'category' => Category::find()->all(),
        ]);
    }

    /**
     * Updates an existing Ads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->author != \Yii::$app->user->identity->username){
            throw new HttpException('403', "Вам не разрешено проводить данное действие");
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'category' => Category::find()->all(),
        ]);
    }

    /**
     * Deletes an existing Ads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->author != \Yii::$app->user->identity->username){
            throw new HttpException('403', "Вам не разрешено проводить данное действие");
        }

        $model->delete();


        return $this->redirect('index');
    }

    /**
     * Finds the Ads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ads::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
