<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m230227_220240_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'book_id',
            'book_author',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'author_id',
            'book_author',
            'author_id',
            'authors',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_author}}');
    }
}
