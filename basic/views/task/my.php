<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
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
            'description: text',
            'creator_at:dataTime',
            'updater_at:dataTime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{share} {view} {update} {delete}',
                'buttons' => [
                        'share' => function ($url, \app\models\Task $model, $key) {
                            $ico = \yii\bootstrap\Html::icon('share');
                            return Html::a($ico, ['task-user/create', 'taskId' => $model -> id]);
                        }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
