<?php

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => 'localhost',
    'port'     => '3306',
    'dbname'   => 'microCMS',
    'user'     => 'root',
    'password' => '41424521',
);

// define log level
$app['monolog.level'] = 'WARNING';