<?php

use yii\db\Migration;

class m250915_103652_alter_locality_status_colum extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%locality}}', 'status', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250915_103652_alter_locality_status_colum cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250915_103652_alter_locality_status_colum cannot be reverted.\n";

        return false;
    }
    */
}
