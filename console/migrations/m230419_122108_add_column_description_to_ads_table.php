<?php

class m230419_122108_add_column_description_to_ads_table extends \yii\db\Migration

{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%ads}}', 'description', $this->string(256));
    }

    public function down()
    {
        $this->dropColumn('{{%ads}}', 'description');
    }

}