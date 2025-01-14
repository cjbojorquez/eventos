<?php
include 'auth/auth.php'; 
require '../db.php';

if (!isset($_GET['id_abono']) || empty($_GET['id_abono'])) {
    die('ID de abono no proporcionado.');
}

$id_abono = intval($_GET['id_abono']);

// Obtener detalles del abono
$queryAbono = "SELECT id, fecha, id_evento, monto, tipo_pago, referencia, id_participante, md5 FROM abono WHERE id = :id_abono";
$stmtAbono = $pdo->prepare($queryAbono);
$stmtAbono->execute(['id_abono' => $id_abono]);
$abono = $stmtAbono->fetch(PDO::FETCH_ASSOC);

if (!$abono) {
    die('Abono no encontrado.');
}

// Obtener detalles del participante
$queryParticipante = "SELECT nombre, apellido, telefono FROM participante WHERE id = :id_participante";
$stmtParticipante = $pdo->prepare($queryParticipante);
$stmtParticipante->execute(['id_participante' => $abono['id_participante']]);
$participante = $stmtParticipante->fetch(PDO::FETCH_ASSOC);

// Obtener el evento
$queryEvento = "SELECT nombre FROM evento WHERE id = :id_evento";
$stmtEvento = $pdo->prepare($queryEvento);
$stmtEvento->execute(['id_evento' => $abono['id_evento']]);
$evento = $stmtEvento->fetch(PDO::FETCH_ASSOC);

// Generar el número de recibo (suponemos que es el ID del abono)
$numero_recibo = $abono['id'];

// Fecha para el MD5 (usamos la fecha del abono)
$fecha_abono = date('Ymd', strtotime($abono['fecha']));

// Generación del hash MD5
$md5 = //md5($abono['monto'] . '|' . $numero_recibo . '|' . $fecha_abono . '|' . $abono['id_participante'] . '|' . $abono['id_evento']);

// Crear el mensaje de resumen del abono
$mensaje_abono = "Resumen del Abono:\n\n";
$mensaje_abono .= "Participante: " . htmlspecialchars($participante['nombre'] . ' ' . $participante['apellido']) . "\n";
$mensaje_abono .= "Evento: " . htmlspecialchars($evento['nombre']) . "\n";
$mensaje_abono .= "Monto: Q" . number_format($abono['monto'], 2) . "\n";
$mensaje_abono .= "Tipo de Pago: " . htmlspecialchars($abono['tipo_pago']) . "\n";
$mensaje_abono .= "Número de Recibo: " . $numero_recibo . "\n";
$mensaje_abono .= "Fecha de Pago: " . $abono['fecha'] . "\n";
$mensaje_abono .= "Referencia: " . $abono['referencia'] . "\n";
$mensaje_abono .= "MD5 del Abono: " .$abono['md5'] . "\n\n";
$mensaje_abono .= "Para más detalles, visita: http://localhost:81/eventos/views/ver_participante.php?id=" . $abono['id_participante'];

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


<div class='alert alert-success'>
    <h4>Resumen del Abono</h4>
    <p><strong>Participante:</strong> <?php echo htmlspecialchars($participante['nombre'] . ' ' . $participante['apellido']); ?></p>
    <p><strong>Evento:</strong> <?php echo htmlspecialchars($evento['nombre']); ?></p>
    <p><strong>Monto:</strong> Q<?php echo number_format($abono['monto'], 2); ?></p>
    <p><strong>Tipo de Pago:</strong> <?php echo htmlspecialchars($abono['tipo_pago']); ?></p>
    <p><strong>Número de Recibo:</strong> <?php echo $numero_recibo; ?></p>
    <p><strong>Fecha de Pago:</strong> <?php echo $abono['fecha']; ?></p>
    <p><strong>MD5 del Abono:</strong> <?php echo htmlspecialchars($abono['md5']); ?></p>
</div>

<!-- Botón para enviar el resumen por WhatsApp -->
<button id='whatsappButton' name='whatsappButton' class='btn btn-success'>Enviar Resumen por WhatsApp</button>

<script>
    document.getElementById('whatsappButton').addEventListener('click', function() {
        var telefono = '<?php echo $participante['telefono']; ?>';
        var mensaje = '<?php echo urlencode($mensaje_abono); ?>';

        // Detectar si es móvil o PC
        var isMobile = /Mobi|Android/i.test(navigator.userAgent);

        var url;
        if (isMobile) {
            // Si es móvil, abrir la aplicación de WhatsApp
            url = 'https://wa.me/' + telefono + '?text=' + mensaje;
        } else {
            // Si es PC, abrir WhatsApp Web
            url = 'https://web.whatsapp.com/send?phone=' + telefono + '&text=' + mensaje;
        }

        window.open(url, '_blank');
    });
</script>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>