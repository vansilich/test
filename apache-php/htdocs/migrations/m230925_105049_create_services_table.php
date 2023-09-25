<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%services}}`.
 */
class m230925_105049_create_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%services}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(300)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL')
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT');

        $this->execute('ALTER TABLE `services` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%services}}');
    }
}
