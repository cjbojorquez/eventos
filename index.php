<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    // Redirigir al login si no hay sesión activa
    header('Location: views/login.php');
    exit;
}

// Si hay sesión, mostrar la página principal
$nombre_usuario = $_SESSION['nombre_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>
    <p>Has iniciado sesión correctamente.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
