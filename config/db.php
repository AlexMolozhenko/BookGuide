<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=book_guide',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

//     Schema cache options (for production environment)
    'enableSchemaCache' => false,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
