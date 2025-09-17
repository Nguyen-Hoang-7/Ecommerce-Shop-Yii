<?php

use yii\db\Migration;

class m250916_151641_add_multi_columns_to_user_addresses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_addresses}}', 'ward_code', $this->string());
        $this->addColumn('{{%user_addresses}}', 'district_code', $this->string());
        $this->addColumn('{{%user_addresses}}', 'province_code', $this->string());
        $this->addColumn('{{%user_addresses}}', 'full_address', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250916_151641_add_multi_columns_to_user_addresses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250916_151641_add_multi_columns_to_user_addresses cannot be reverted.\n";

        return false;
    }
    */
}
