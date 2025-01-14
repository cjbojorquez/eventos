<?php
include 'auth/auth.php';
require '../db.php';

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}

// Obtener la lista de participantes
$query = "SELECT p.id, p.nombre, p.apellido,p.telefono, e.nombre AS evento, COALESCE(SUM(a.monto), 0) AS saldo
          FROM participante p
          JOIN evento e ON p.id_evento = e.id
          LEFT JOIN abono a ON p.id = a.id_participante
          GROUP BY p.id, p.nombre, p.apellido, e.nombre";
$stmt = $pdo->query($query);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Obtener resumen de abonos
$queryTotalAbonos = "SELECT COALESCE(SUM(monto), 0) AS total FROM abono";
$stmtTotal = $pdo->query($queryTotalAbonos);
$totalAbonos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener abonos del día
$queryAbonosDia = "SELECT COALESCE(SUM(monto), 0) AS total FROM abono WHERE DATE(fecha) = CURDATE()";
$stmtDia = $pdo->query($queryAbonosDia);
$abonosDia = $stmtDia->fetch(PDO::FETCH_ASSOC)['total'];

// Total de ingresos agrupados por tipo_pago
$queryTotalPorTipo = "SELECT tipo_pago, COALESCE(SUM(monto), 0) AS total 
                      FROM abono 
                      GROUP BY tipo_pago";
$stmtTotalPorTipo = $pdo->query($queryTotalPorTipo);
$totalPorTipo = $stmtTotalPorTipo->fetchAll(PDO::FETCH_ASSOC);

// Ingresos del día agrupados por tipo_pago
$queryDiaPorTipo = "SELECT tipo_pago, COALESCE(SUM(monto), 0) AS total 
                    FROM abono 
                    WHERE DATE(fecha) = CURDATE() 
                    GROUP BY tipo_pago";
$stmtDiaPorTipo = $pdo->query($queryDiaPorTipo);
$diaPorTipo = $stmtDiaPorTipo->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Participantes</title>
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
            <h2>Listado de Participantes</h2>
            <div class="row">
                <!-- Card para la lista de participantes -->
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Evento</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($participantes as $participante): ?>
                                        <tr>
                                            <td><?php echo $participante['id']; ?></td>
                                            <td><?php echo htmlspecialchars($participante['nombre'] . ' ' . $participante['apellido']); ?></td>
                                            <td><?php echo htmlspecialchars($participante['evento']); ?></td>
                                            <td>Q<?php echo number_format($participante['saldo'], 2); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="abrirModalPago(<?php echo $participante['id']; ?>)"><i class="fa-solid fa-money-bill-1-wave"></i> Ingresar Pago</button>
                                                <a class="btn btn-secondary btn-sm" role="button" href="participante_form.php?id=<?php echo $participante['id']; ?>"><i class="fa-solid fa-user-pen"></i> Modificar</a>
                                                <a class="btn btn-primary btn-sm" role="button" href="ver_participante.php?id=<?php echo $participante['id']; ?>"><i class="fa-solid fa-eye"></i> Ver</a>
                                                
                                                <button class="btn btn-success btn-sm" id="whatsappButton">
                                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                                </button>
                                                <?php if ($_SESSION['nombre_usuario'] === 'admin'): ?>
                                                    <a class="btn btn-danger btn-sm" role="button" href="cambiar_estado.php?id=<?php echo $participante['id']; ?>"><i class="fa-solid fa-user-xmark"></i> Eliminar</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Cards adicionales -->
                 
                <div class="col-lg-2">
                    <!-- Card Minimo -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            Meta
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Monto a recaudar</h5>
                            <p class="card-text">Q<?php echo number_format(600*100, 2); ?></p>
                        </div>
                    </div>
                    <!-- Card Resumen de Abonos -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            Total Ingresos
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Total Abonos</h5>
                            <ul class="list-group">
                                <?php foreach ($totalPorTipo as $tipo): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($tipo['tipo_pago']); ?>
                                        <span>Q<?php echo number_format($tipo['total'], 2); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="mt-3">
                                <strong>Total:</strong> Q<?php echo number_format($totalAbonos, 2); ?>
                            </div>
                        </div>
                    </div>


                    <!-- Card Abonos del Día -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            Ingresos del D&iacutea
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Abonos del d&iacutea</h5>
                            <ul class="list-group">
                                <?php foreach ($diaPorTipo as $tipo): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($tipo['tipo_pago']); ?>
                                        <span>Q<?php echo number_format($tipo['total'], 2); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="mt-3">
                                <strong>Total:</strong> Q<?php echo number_format($abonosDia, 2); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ingresar Pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPagoLabel">Ingresar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalPagoBody">
                <!-- Contenido dinámico se cargará aquí -->
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'partials/footer.php'; ?>

<!-- Bootstrap JS & Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
function abrirModalPago(idParticipante) {
    $('#modalPagoBody').html('<div class="text-center">Cargando...</div>');
    $('#modalPago').modal('show');
    $.ajax({
        url: 'ingresar_pago.php',
        type: 'GET',
        data: { id: idParticipante },
        success: function(response) {
            $('#modalPagoBody').html(response);
        },
        error: function() {
            $('#modalPagoBody').html('<div class="text-danger">Error al cargar el formulario de pago.</div>');
        }
    });
}

// Actualizar la lista de participantes al cerrar el modal
$('#modalPago').on('hidden.bs.modal', function () {
    location.reload();
});
</script>
<script>
    // Detectar si el usuario está en un dispositivo móvil
    function isMobile() {
        return /Mobi|Android/i.test(navigator.userAgent);
    }

    // Función para abrir WhatsApp con mensaje personalizado
    function openWhatsApp(idParticipante, telefono) {
        var mensaje = "¡Hola! Te damos la bienvenida a nuestro evento. Aquí está el resumen de tu inscripción: ";
        var urlParticipante = "http://localhost:81/eventos/views/ver_participante.php?id=" + idParticipante;
        var mensajeCompleto = encodeURIComponent(mensaje + urlParticipante);

        // Asegurarse de que el número de teléfono sea el adecuado
        var telefonoConCodigo = encodeURIComponent(telefono.replace(/[^0-9]/g, '')); // Eliminar caracteres no numéricos
        
        if (isMobile()) {
            // Si es un dispositivo móvil, abre la app de WhatsApp
            window.location.href = "whatsapp://send?phone=" + telefonoConCodigo + "&text=" + mensajeCompleto;
        } else {
            // Si es una computadora, abre WhatsApp Web
            window.open("https://web.whatsapp.com/send?phone=" + telefonoConCodigo + "&text=" + mensajeCompleto, '_blank');
        }
    }

    // Agregar el evento click al botón de WhatsApp
    document.getElementById("whatsappButton").addEventListener("click", function() {
        var idParticipante = <?php echo $participante['id']; ?>; // Usar el ID del participante
        var telefono = "<?php echo $participante['telefono']; ?>"; // Usar el teléfono del participante
        openWhatsApp(idParticipante, telefono);
    });
</script>
</body>
</html>
