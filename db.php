<?php
$host = 'localhost';
$dbname = 'eventos_db';
$username = 'usr_evento'; // Cambiar según  configuración
$password = 'c0n7r453n4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar: " . $e->getMessage());
}
?>
