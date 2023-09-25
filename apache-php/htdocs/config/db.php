<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf('mysql:host=%s:%d;dbname=%s', $_ENV['DB_APP_HOST'], $_ENV['DB_APP_PORT'], $_ENV['DB_APP_DB_NAME']),
    'username' => $_ENV['DB_APP_USERNAME'],
    'password' => $_ENV['DB_APP_PASSWORD'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
