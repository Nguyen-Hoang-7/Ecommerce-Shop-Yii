<?php

use yii\db\Migration;

class m250916_151130_drop_multi_columns_from_user_addresses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%user_addresses}}', 'city');
        $this->dropColumn('{{%user_addresses}}', 'state');
        $this->dropColumn('{{%user_addresses}}', 'country');
        $this->dropColumn('{{%user_addresses}}', 'zipcode');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250916_151130_drop_multi_columns_from_user_addresses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250916_151130_drop_multi_columns_from_user_addresses cannot be reverted.\n";

        return false;
    }
    */
}
