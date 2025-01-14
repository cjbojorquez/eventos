<?php
include 'auth/auth.php'; 

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    // Redirigir al login si no hay sesión activa
    header('Location: login.php');
    exit;
}

// Si hay sesión, mostrar la página principal
$nombre_usuario = $_SESSION['nombre_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                <h1>Bienvenido al Dashboard</h1>
                <p>Contenido del dashboard...</p>
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
