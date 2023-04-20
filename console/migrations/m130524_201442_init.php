<?php


use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        /*
         * Таблица пользователей
         */
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->notNull()->unique(),
            'nickname' => $this->string(20)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->string()->notNull(),
            'created_at' => $this->string(20)->notNull(),
            'updated_at' => $this->string(20)->notNull(),
        ], $tableOptions);


        /*
         * Таблица категорий
         */
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(20)->notNull()
        ], $tableOptions);


        /*
         * Таблица объявлений
         */
        $this->createTable('{{%ads}}', [
                'id' => $this->primaryKey(),
                'title' => $this->string(20)->notNull(),
                'author' => $this->string(20)->notNull(),
                'category' => $this->integer(),
                'status' => $this->string()->notNull()->defaultValue('На проверке'),
                'created_at' => $this->string(20)->notNull(),
                'updated_at' => $this->string(20)->notNull(),
            ]
        );
        $this->addForeignKey('FK_post_category', '{{%ads}}', 'category', '{{%category}}', 'id', 'SET NULL', 'CASCADE');

    }


    public function down()
    {
        $this->dropTable('{{%ads}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%user}}');
    }
}
