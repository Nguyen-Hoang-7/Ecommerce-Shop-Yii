<?php

use yii\db\Migration;

class m250915_102324_locality_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('locality', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'parent_id' => $this->integer(),
            'locality_type' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'parent_code' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250915_102324_locality_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250915_102324_locality_table cannot be reverted.\n";

        return false;
    }
    */
}
