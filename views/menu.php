
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilo Personalizado (opcional) -->
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">Mi Aplicación</div>
            <div class="list-group list-group-flush">
                <a href="crear_usuario.php" class="list-group-item list-group-item-action bg-dark text-white">Crear Usuario</a>
                <a href="cambiar_contrasena.php" class="list-group-item list-group-item-action bg-dark text-white">Cambiar Contraseña</a>
                <a href="menu.php" class="list-group-item list-group-item-action bg-dark text-white">Menú Principal</a>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="cambiar_contrasena.php">Cambiar Contraseña</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="container mt-5">
                <!-- Aquí va el contenido de la página específica -->
                <h1>Página Principal</h1>
                <p>Contenido aquí...</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5 JS y dependencias (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

