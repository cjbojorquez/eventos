<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Bienvenido a la aplicación</h1>
    <p>Has iniciado sesión correctamente.</p>
    <a href="../controllers/logout.php">Cerrar sesión</a>
</body>
</html>