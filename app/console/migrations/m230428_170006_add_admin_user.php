<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m230428_170006_add_admin_user
 */
class m230428_170006_add_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $model = new User();
      $model->username = 'admin';
      $model->email = 'admin@admin.com';
      $model->status = User::STATUS_ACTIVE;
      $model->setPassword('admin');
      $model->generateAuthKey();

      $model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        User::deleteAll(['username' => 'admin']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230428_170006_add_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
