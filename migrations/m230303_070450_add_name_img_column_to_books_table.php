<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%books}}`.
 */
class m230303_070450_add_name_img_column_to_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('books', 'name_img', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('books', 'name_img');
    }
}
