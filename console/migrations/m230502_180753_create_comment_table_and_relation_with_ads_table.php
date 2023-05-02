<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment_table_and_relation_with_ads}}`.
 */
class m230502_180753_create_comment_table_and_relation_with_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'content' => $this->string()->notNull(),
            'author' => $this->integer(),
            'ads' => $this->integer(),
            'created_at' => $this->string(20)->notNull(),
            'updated_at' => $this->string(20)->notNull(),
        ]);

        $this->addForeignKey('fk-ads-comments', '{{%comments}}', 'ads' , \common\models\Ads::tableName(), 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey('fk-author-comments', '{{%comments}}', 'author' , \common\models\User::tableName(), 'id', 'SET NULL', 'CASCADE' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ads-comments' , '{{%comments}}');
        $this->dropTable('{{%comments}}');
    }
}
