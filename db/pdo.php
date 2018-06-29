<?php

    // if we didnt receive any POST data - send 404
    if (empty($_POST)) {
        header("HTTP/1.0 404 Not Found");
        echo 'HTTP/1.0 404 Not Found';
        exit;
    }

// local db
$db_host = 'localhost';
$db_user = 'root';
$db_name = 'reservationsbmsd';
$db_pass = '';
$charset = 'utf8';

// work db
// $db_host = 'localhost';
// $db_user = 'u_reservat';
// $db_name = 'reservations';
// $db_pass = '6n7NNCnY';
// $charset = 'utf8';

/**
 * Connect to DB
 */
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $db_user, $db_pass, $opt);
