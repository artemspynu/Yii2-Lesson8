<?php

namespace app\controllers;

use Yii;
use app\models\TaskUser;
use app\models\search\TaskUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserTaskController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'unshared-all' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($taskId)
    {
        $task = Task:: findOne($taskId);
        if (!$task || $task->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        $model = new TaskUser();
        $model->task_id = $taskId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Доступ получен');
            return $this->redirect(['task/my']);
        }
        $users = User::find()
            ->where(['<>', 'id', Yii::$app->user->id])
            ->select('username')
            ->indexBy('id')
            ->column();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUnsharedAll($taskId)
    {
        $task = Task:: findOne($taskId);
        if (!$task || $task->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        $task->unlinkAll(Task::RELATION_SHARED_USERS, true);
        Yii::$app->session->setFlash('success', 'Доступ удален');
        return $this->redirect(['task/shared']);
    }

    /**
     * Deletes an existing TaskUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteAll($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['task/shared']);
    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
