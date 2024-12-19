<?php 

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    // Redirigir al login si no hay sesión activa
    header('Location: views/login.php');
    exit;
}

?>