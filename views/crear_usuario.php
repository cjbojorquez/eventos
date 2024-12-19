<?php
include 'auth/auth.php'; 
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
        $success_message = "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
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
            <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Crear Usuario</h3>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar mensaje de éxito o error -->
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php elseif (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario de creación de usuario -->
                        <form method="POST" action="crear_usuario.php">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Completo" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            </div>

                            <div class="mb-3">
                                <label for="grupo_id" class="form-label">ID del Grupo</label>
                                <input type="number" class="form-control" id="grupo_id" name="grupo_id" placeholder="ID del Grupo" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
