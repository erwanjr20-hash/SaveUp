<?php

$host = "localhost";
$user = "root";
$pass = "";
$database = "db_aplikasi_keuangan_pribadi";

$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "Koneksi tidak tersambung" . $error->getMessage();
}