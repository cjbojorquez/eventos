<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encriptar la contraseña con MD5
    $grupo_id = $_POST['grupo_id'];

    $query = "INSERT INTO usuario (usuario, nombre, email, password, grupo_id) 
              VALUES (:usuario, :nombre, :email, :password, :grupo_id)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'usuario' => $usuario,
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'grupo_id' => $grupo_id,
        ]);
        echo "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <form method="POST" action="crear_usuario.php">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="text" name="nombre" placeholder="Nombre Completo" required>
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="number" name="grupo_id" placeholder="ID del Grupo" required>
        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
