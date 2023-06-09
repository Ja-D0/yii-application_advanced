<?php

namespace backend\controllers;

use common\models\Ads;
use backend\models\AdsSearch;
use common\models\Category;
use common\models\Comments;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                                'actions' => ['index', 'view', 'update', 'create' , 'delete', 'ads'],
                                'allow' => true,
                            //    'roles' => ['@'],
                            //    'matchCallback' => function ($rule, $action) {
                            //        $user = Yii::$app->user->identity;
                            //        return $user->status == 'Администратор';
                            //    }
                                'roles' => ['admin']
                            ],

                        ],
                    ],

                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
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
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ads model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     *
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

                if ($model->save()){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => Category::find()->all()
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => Category::find()->all(),
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
