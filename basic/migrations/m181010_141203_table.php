<?php

use yii\db\Migration;

/**
 * Class m181010_141203_table
 */
class m181010_141203_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notnull(),
            'description' => $this->text()->notnull(),
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
        $this->dropTable('task');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181010_141203_table cannot be reverted.\n";

        return false;
    }
    */
}
