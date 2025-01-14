<?php
//include 'auth/auth.php'; 
session_start();
require '../db.php';

// Inicializar variables
$id = $nombre = $apellido = $email = $telefono = $dpi = $fecha_nacimiento = $talla = $responsable = $parentesco = $telefono_responsable = '';
$saldo_restante = $total_abonos = 0;
$detalles_abonos = [];

// Verificar si existe el ID del participante
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener información del participante
    $stmt = $pdo->prepare("SELECT * FROM participante WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $participante = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($participante) {
        extract($participante);
    }
    
    // Obtener el total de abonos realizados por el participante
    $stmt = $pdo->prepare("SELECT SUM(monto) AS total_abonos FROM abono WHERE id_participante = :id");
    $stmt->execute(['id' => $id]);
    $abonos = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_abonos = $abonos['total_abonos'] ?? 0;
    
    // Obtener el detalle de los abonos
    $stmt = $pdo->prepare("SELECT fecha, monto, md5 FROM abono WHERE id_participante = :id ORDER BY fecha ASC");
    $stmt->execute(['id' => $id]);
    $detalles_abonos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener el precio del evento
    $stmt = $pdo->prepare("SELECT precio FROM evento WHERE id = :id_evento");
    $stmt->execute(['id_evento' => $id_evento]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);
    $precio_evento = $evento['precio'] ?? 0;
    
    // Calcular saldo restante
    $saldo_restante = $precio_evento - $total_abonos;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Participante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<div class="d-flex" id="wrapper">
    <?php include 'partials/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <?php include 'partials/navbar.php'; ?>

        <div class="container mt-5">
            <div class="row">
                <!-- Card principal con información del participante -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3>Información del Participante</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
                            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($apellido); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                            <p><strong>DPI:</strong> <?php echo htmlspecialchars($dpi); ?></p>
                            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
                            <p><strong>Talla:</strong> <?php echo htmlspecialchars($talla); ?></p>
                            <p><strong>Responsable:</strong> <?php echo htmlspecialchars($responsable); ?></p>
                            <p><strong>Parentesco:</strong> <?php echo htmlspecialchars($parentesco); ?></p>
                            <p><strong>Teléfono del Responsable:</strong> <?php echo htmlspecialchars($telefono_responsable); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Cards de abonos y saldo -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3>Total de Abonos</h3>
                        </div>
                        <div class="card-body">
                            <h4 class="text-success">$<?php echo number_format($total_abonos, 2); ?></h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3>Saldo Restante</h3>
                        </div>
                        <div class="card-body">
                            <h4 class="text-danger">$<?php echo number_format($saldo_restante, 2); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de detalles de abonos -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Detalle de Abonos</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>MD5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalles_abonos as $abono): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($abono['fecha']) ?></td>
                                            <td>$<?php echo number_format($abono['monto'], 2); ?></td>
                                            <td><?php if(isset($_SESSION['nombre_usuario']))echo htmlspecialchars($abono['md5']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>$<?php echo number_format($total_abonos, 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
