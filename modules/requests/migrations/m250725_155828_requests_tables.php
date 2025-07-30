<?php

use yii\db\Migration;

class m250725_155828_requests_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('requests', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'status' => $this->integer(),
            'images' => 'integer[]',
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Add function for update date at column
        $this->execute("
        CREATE OR REPLACE FUNCTION updated_at_date()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = NOW();
                RETURN NEW;
            END;
            $$ language 'plpgsql';
        ");

        // Attach trigger to requests table
        $this->execute('
            CREATE TRIGGER update_updated_at BEFORE UPDATE ON requests
            FOR EACH ROW EXECUTE PROCEDURE updated_at_date();
        ');

        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'requests_id' => $this->integer(),
            'comment' => $this->text(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createTable('images', [
            'id' => $this->primaryKey(),
            'url' => $this->string(255),
            'alt' => $this->string(255),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('images');
        $this->dropTable('comments');
        $this->dropTable('requests');
        $this->execute('DROP FUNCTION IF EXISTS updated_at_date()');
        $this->execute('DROP TRIGGER IF EXISTS update_updated_at ON requests');
    }
}
