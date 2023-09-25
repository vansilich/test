<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m230925_095611_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'link' => $this->string(300)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_estonian_ci NOT NULL'),
            'quantity' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->comment("COMMENT '0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail'"),
            'created_at' => $this->integer()->notNull(),
            'mode' => $this->tinyInteger(1)->notNull()->comment('0 - Manual, 1 - Auto'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT');

        $this->execute('ALTER TABLE `orders` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100001');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }
}
