<?php

use yii\db\Migration;

/**
 * Class m181010_141740_table
 */
class m181010_141740_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_user', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notnull(),
            'user_id' => $this->integer()->notnull(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task_user');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181010_141740_table cannot be reverted.\n";

        return false;
    }
    */
}
