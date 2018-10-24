<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shared Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            'description: ntext',
            [
                'label' => 'users',
                'value' => function (\app\models\Task $model) {
                    $usernames = \yii\helpers\ArrayHelper::map($model->taskUsers, 'user_id', 'user.username');
                    return join(',', $usernames);
                }
            ],
            'creator_at:dataTime',
            'updater_at:dataTime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{unshared} {view} {update} {delete}',
                'buttons' => [
                    'unshared' => function ($url, \app\models\Task $model, $key) {
                        $ico = \yii\bootstrap\Html::icon('ban-circle');
                        return Html::a($ico,
                            ['task-user/unshared-all', 'taskId' => $model->id], [
                                'data' => [
                                    'confirm' => 'Удалить доступ для всех?',
                                    'method' => 'post',
                                    ]
                            ]);
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
