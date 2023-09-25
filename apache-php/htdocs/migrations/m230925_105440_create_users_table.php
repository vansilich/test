<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m230925_105440_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(300)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL'),
            'last_name' => $this->string(300)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL')
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT');

        $this->execute('ALTER TABLE `users` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
