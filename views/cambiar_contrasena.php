<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $nueva_password = md5($_POST['nueva_password']); // Encriptar la nueva contraseña

    $query = "UPDATE usuario SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute(['password' => $nueva_password, 'id' => $usuario_id]);
        echo "Contraseña actualizada exitosamente para el usuario seleccionado.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener usuarios para el formulario
$query = "SELECT id, usuario FROM usuario";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña de Otros Usuarios</title>
</head>
<body>
    <h1>Cambiar Contraseña de Otros Usuarios</h1>
    <form method="POST" action="cambiar_contrasena.php">
        <select name="usuario_id" required>
            <option value="">Seleccione un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= htmlspecialchars($usuario['id']) ?>">
                    <?= htmlspecialchars($usuario['usuario']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="password" name="nueva_password" placeholder="Nueva Contraseña" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <?php include 'partials/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <!-- Navbar -->
            <?php include 'partials/navbar.php'; ?>

            <!-- Content Area -->
            <div class="container mt-5">
                <h2>Cambiar Contraseña</h2>
                <form method="POST" action="cambiar_contrasena.php">
                    <div class="mb-3">
                        <label for="usuario_id" class="form-label">Seleccionar Usuario</label>
                        <select name="usuario_id" id="usuario_id" class="form-select" required>
                            <option value="">Seleccione un usuario</option>
                            <!-- Aquí deben ir los usuarios de la base de datos -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nueva_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="nueva_password" id="nueva_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Bootstrap 5 JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
