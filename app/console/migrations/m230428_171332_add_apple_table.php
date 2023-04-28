<?php

use yii\db\Migration;

/**
 * Class m230428_171332_add_apple_table
 */
class m230428_171332_add_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('{{%apple}}', [
        'id' => $this->primaryKey(),
        'color' => $this->string()->notNull(),
        'created_at' => $this->timestamp()->notNull(),
        'drop_at' => $this->timestamp(),
        'status' => $this->tinyInteger()->defaultValue(\common\models\Apple::STATUS_ON_TREE)->notNull(),
        'size' => $this->decimal(10, 2)->defaultValue(1.0)->notNull(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230428_171332_add_apple_table cannot be reverted.\n";

        return false;
    }
    */
}
