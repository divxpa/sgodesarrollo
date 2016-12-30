<?php

// datos para la conexión a mysql 
include_once(__DIR__."/../../global/db.php");

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.DB_SERVER.';dbname='.DB_NAME, 'username' => DB_USER, 'password' => DB_PASS,
    //'dsn' => 'mysql:host=45.55.148.128:3306;dbname=mospneuquen_db', 'username' => 'sgoread', 'password' => 'phb*1sx123',
    'charset' => 'utf8',
];
