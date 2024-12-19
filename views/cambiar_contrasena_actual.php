<?php
include 'auth/auth.php'; 
require '../db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nueva_password = md5($_POST['nueva_password']); // Encriptar la nueva contraseña

    $query = "UPDATE usuario SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute(['password' => $nueva_password, 'id' => $usuario_id]);
        echo "Contraseña actualizada exitosamente.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <h1>Cambiar Contraseña</h1>
    <form method="POST" action="cambiar_contrasena_actual.php">
        <input type="password" name="nueva_password" placeholder="Nueva Contraseña" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
</body>
</html>
