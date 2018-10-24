<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlamableBehavior;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password_hash
 * @property string $access_token
 * @property string $auth_key
 * @property int $creator_id
 * @property int $updater_id
 * @property int $creator_at
 * @property int $updater_at
 *
 * @property Task[] $tasksCreated
 * @property Task[] $tasksUpdated
 * @property TaskUser[] $taskUsers
 * @property Task[] $sharedTasks
 * @property Task[] $accessedTasks
 * @mixin TimestampBehavior
 * @mixin BlameableBehavior
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password;

    const RELATION_TASK_CREATED = 'tasksCreated';
    const RELATION_TASK_USERS = 'tasksUsers';
    const RELATION_TASK_SHARED = 'sharedTasks';
    const RELATION_TASK_ACCESSED = 'accessedTasks';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DEFAULT = 'default';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            $this->auth_key = \Yii::$app->getSecurity()->generateRandomString();
        }
        if ($this->password) {
            $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        return true;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ]
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['username', 'name'],
            self::SCENARIO_CREATE => ['username', 'name', 'password'],
            self::SCENARIO_UPDATE => ['username', 'name'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'name'], 'required'],
            [['creator_id', 'updater_id', 'creator_at', 'updater_at'], 'integer'],
            [['username', 'name', 'password', 'access_token', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'password_hash' => 'Password Hash',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'creator_at' => 'Creator At',
            'updater_at' => 'Updater At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksCreated()
    {
        return $this->hasMany(Task::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksUpdated()
    {
        return $this->hasMany(Task::className(), ['updater_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers()
    {
        return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSharedTasks()
    {

        return $this->hasMany(Task::className(), ['id' => 'task_id'])
            ->via(self::RELATION_TASKS_USERS);
    }

    public function getAccessedTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])
            ->via(self::RELATION_TASKS_USERS);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserQuery(get_called_class());
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}

