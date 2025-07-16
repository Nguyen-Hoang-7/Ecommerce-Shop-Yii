<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250702_063929_add_firstname_lastname_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'firstname', $this->string(255)->after('id'));
        $this->addColumn('{{%user}}', 'lastname', $this->string(255)->after('firstname'));

        // Gán giá trị tạm để tránh lỗi
    Yii::$app->db->createCommand()->update('{{%user}}', [
        'firstname' => 'Goku',
        'lastname' => 'Black',
    ])->execute();

    // ALTER thành NOT NULL
    $this->alterColumn('{{%user}}', 'firstname', $this->string(255)->notNull());
    $this->alterColumn('{{%user}}', 'lastname', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'firstname');
        $this->dropColumn('{{%user}}', 'lastname');
    }
}
