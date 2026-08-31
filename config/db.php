<?php
session_start();

$host = 'localhost';
$dbname = 'pet_db';
$username = 'pet_user';
$password = 'pet_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("CRITICAL_SYSTEM_ERROR: " . $e->getMessage());
}


$site_url = "http://localhost/pet/";
?>