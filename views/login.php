<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT password FROM usuario WHERE usuario = :nombre_usuario";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nombre_usuario' => $nombre_usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && md5($contrasena)== $user['password']) {
        // Iniciar sesión
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST" action="login.php">
        <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
