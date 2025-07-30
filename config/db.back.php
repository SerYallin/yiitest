<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=YOUR_DATABASE_NAME',
    'username' => 'YOUR_DATABASE_USERNAME',
    'password' => 'YOUR_DATABASE_PASSWORD',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
