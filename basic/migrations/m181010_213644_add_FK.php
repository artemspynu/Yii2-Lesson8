<?php

use yii\db\Migration;

/**
 * Class m181010_213644_add_FK
 */
class m181010_213644_add_FK extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fx_taskuser_user', 'task_user', ['user_id'], 'user', ['id']);
        $this->addForeignKey('fx_taskuser_task', 'task_user', ['task_id'], 'task', ['id']);
        $this->addForeignKey('fx_task_user1', 'task', ['creator_id'], 'user', ['id']);
        $this->addForeignKey('fx_task_user2', 'task', ['updater_id'], 'user', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fx_taskuser_user', 'task_user', ['user_id'], 'user', ['id']);
        $this->dropForeignKey('fx_taskuser_task', 'task_user', ['task_id'], 'task', ['id']);
        $this->dropForeignKey('fx_task_user1', 'task', ['creator_id'], 'user', ['id']);
        $this->dropForeignKey('fx_task_user2', 'task', ['updater_id'], 'user', ['id']);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181010_213644_add_FK cannot be reverted.\n";

        return false;
    }
    */
}
