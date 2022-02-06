<?php

define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');
define('MYSQL_HOST','localhost:90');
define('MYSQL_PORT','3307');
define('MYSQL_DATABASE','ap_shopping');

$options=[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
];

$db=new PDO('mysql:dbhost='.MYSQL_HOST.';port='.MYSQL_PORT.';dbname='.MYSQL_DATABASE,MYSQL_USER,MYSQL_PASSWORD,$options);
// $statement=$db->query("SELECT * FROM users");
// $result=$statement->fetchAll();
// print_r($result);