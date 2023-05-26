<?php

namespace frontend\controllers;

use common\models\Comments;
use backend\models\CommentsSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
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
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $view = $model->ads0->id;
        if ($model->author != \Yii::$app->user->identity->id){
            throw new HttpException('403', "Вам не разрешено проводить данное действие");
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['../ads/view', 'id' => $view]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $view = $model->ads0->id;
        if ($model->author != \Yii::$app->user->identity->id){
            throw new HttpException('403', "Вам не разрешено проводить данное действие");
        }

        $model->delete();

        return $this->redirect(['../ads/view' , 'id' => $view]);
    }

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comments::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
