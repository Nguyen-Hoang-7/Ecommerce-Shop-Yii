<?php

use yii\db\Migration;

class m250916_151543_drop_multi_columns_from_order_addresses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%order_addresses}}', 'city');
        $this->dropColumn('{{%order_addresses}}', 'state');
        $this->dropColumn('{{%order_addresses}}', 'country');
        $this->dropColumn('{{%order_addresses}}', 'zipcode');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250916_151543_drop_multi_columns_from_order_addresses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250916_151543_drop_multi_columns_from_order_addresses cannot be reverted.\n";

        return false;
    }
    */
}
