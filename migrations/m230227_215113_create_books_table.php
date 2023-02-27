<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m230227_215113_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()->notNull(),
            'description'=>$this->text(),
            'publication_date'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
