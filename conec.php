<?php
// config/db.php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'TOYS';
$DB_USER = 'toys_user'; // cámbialo por tu usuario
$DB_PASS = 'toys_pass'; // y tu contraseña
$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
try { $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options); }
catch (PDOException $e) { exit('Error de conexión DB: '.$e->getMessage()); }