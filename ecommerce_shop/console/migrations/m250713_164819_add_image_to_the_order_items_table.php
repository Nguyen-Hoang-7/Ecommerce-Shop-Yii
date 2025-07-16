<?php

use yii\db\Migration;

class m250713_164819_add_image_to_the_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{orders}}', 'paypal_order_id', $this->string(255)->after('transaction_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250713_164819_add_image_to_the_order_items_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250713_164819_add_image_to_the_order_items_table cannot be reverted.\n";

        return false;
    }
    */
}
