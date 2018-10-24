<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\base\BaseObject;
use yii\web\Controller;


/**
 * Class A
 *
 * @package app\controllers
 * @property int $prop
 */
class A extends BaseObject
{
    private $prop;

    public function getProp()
    {
        return $this->prop;
    }

    public function setProp($value)
    {
        return $this->prop = $value;
    }
}


/**
 * Class TestController
 * @package app\controllers
 */
class TestController extends Controller
{
    public function actionIndex()
    {
//        \Yii::setAlias('test', 'task/my');
//        return Url::to('@test');
        return \Yii::t('yii',
            'Condition for "{attribute}" should be either a value or valid operator specification.',
            ['attribute' => 'ATTR']);
        return $this->render('index', [
            'result' => $result
        ]);
    }

    public function actionInsert()
    {
        $result = \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'root',
            'name' => 'Artem',
            'password_hash' => 1234,
            'access_token' => '58e19cd2f980f32aa1cadcc14a33dd0e218a48fd',
            'auth_key' => 1,
            'creator_id' => 341,
            'updater_id' => 42,
            'creator_at' => 124,
            'updater_at' => 452,
        ])->execute();


        $result = \Yii::$app->db->createCommand()->batchInsert('task', ['creator_id', 'id'],
            [
                [1, 1],
                [2, 2],
                [3, 3],

            ])->execute();

        return $this->render('index', [
            'result' => $result

        ]);

        return VarDumper::dumpAsString($result);
    }

    public function actionSelect()
    {
        $id = 1;
        $query = (new Query())->from('user')->select(['id'])->where(['=', 'id', $id]);
        $result = $query->one;
        $query = (new Query())->from('user')->select(['id'])->where(['>', 'id', $id])->orderBy('name');
        $result = $query->all;
        $query = (new Query())->from('user')->count('*');
        $result = $query->all;
        $query = (new Query())->from('task')->select(['creator_id'])->innerJoin('user', 'task');
        $result = $query->all;


        return VarDumper::dumpAsString($result);

    }
}

