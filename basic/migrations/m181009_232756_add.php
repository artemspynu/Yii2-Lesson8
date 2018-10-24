<?php

use yii\db\Migration;

/**
 * Class m181009_232756_add
 */
class m181009_232756_add extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notnull(),
            'name' => $this->string(255)->notnull(),
            'password_hash' => $this->string(255)->notnull(),
            'access_token' => $this->string(255),
            'auth_key' => $this->string(255),
            'creator_id' => $this->integer()->notnull(),
            'updater_id' => $this->integer(),
            'creator_at' => $this->integer()->notnull(),
            'updater_at' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181009_232756_add cannot be reverted.\n";

        return false;
    }
    */
}
