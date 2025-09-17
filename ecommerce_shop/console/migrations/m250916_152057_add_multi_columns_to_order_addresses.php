<?php

use yii\db\Migration;

class m250916_152057_add_multi_columns_to_order_addresses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_addresses}}', 'ward_code', $this->string());
        $this->addColumn('{{%order_addresses}}', 'district_code', $this->string());
        $this->addColumn('{{%order_addresses}}', 'province_code', $this->string());
        $this->addColumn('{{%order_addresses}}', 'full_address', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250916_152057_add_multi_columns_to_order_addresses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250916_152057_add_multi_columns_to_order_addresses cannot be reverted.\n";

        return false;
    }
    */
}
