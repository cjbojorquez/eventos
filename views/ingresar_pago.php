<?php
include 'auth/auth.php'; 
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ((!isset($_GET['id']) || empty($_GET['id']))) {
        die('ID de participante no proporcionado.');
    }
    $id_participante = intval($_GET['id']);
}

$usuario = $_SESSION['nombre_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_participante = $_POST['id_participante'];
    $monto = $_POST['monto'];
    $tipo_pago = $_POST['tipo_pago'];
    $referencia = $_POST['referencia'] ?? null;
    $id_evento = $_SESSION['id_evento'];

    if (is_numeric($monto) && $monto > 0) {
        // Inserción del abono
        $query = "INSERT INTO abono (id_participante, id_evento, usuario, monto, tipo_pago, referencia) 
                  VALUES (:id_participante, :id_evento, :usuario, :monto, :tipo_pago, :referencia)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id_participante' => $id_participante,
            'id_evento' => $id_evento,
            'usuario' => $usuario,
            'monto' => $monto,
            'tipo_pago' => $tipo_pago,
            'referencia' => $referencia,
        ]);

        // Obtener el id del abono insertado
        $id_abono = $pdo->lastInsertId();

        // Generar el hash MD5
        $fecha_abono = date('Ymd'); // Usa la fecha actual si no tienes una en la base de datos
        $numero_recibo = $id_abono;
        $md5 = md5($monto . '|' . $numero_recibo . '|' . $fecha_abono . '|' . $id_participante . '|' . $id_evento);

        // Actualizar el campo md5 en la tabla abono
        $queryUpdate = "UPDATE abono SET md5 = :md5 WHERE id = :id_abono";
        $stmtUpdate = $pdo->prepare($queryUpdate);
        $stmtUpdate->execute([
            'md5' => $md5,
            'id_abono' => $id_abono,
        ]);
        
        // Redirigir al resumen de pago
        header("Location: resumen_pago.php?id_abono=$id_abono");
        
        exit();
    } else {
        echo '<div class="alert alert-danger">El monto debe ser positivo.</div>';
    }
}

// Obtener datos del participante para mostrar en el formulario
$query = "SELECT nombre, apellido, telefono FROM participante WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id_participante]);
$participante = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="POST" action="ingresar_pago.php">
    <input type="hidden" value="<?php echo "$id_participante";?>" name="id_participante">
    <div class="mb-3">
        <label for="monto" class="form-label">Monto</label>
        <input type="number" step="0.01" class="form-control" name="monto" required>
    </div>
    <div class="mb-3">
        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
        <select class="form-select" name="tipo_pago" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Tarjeta">Tarjeta</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="referencia" class="form-label">Referencia</label>
        <input type="text" class="form-control" name="referencia">
    </div>
    <button type="submit" class="btn btn-primary">💾 Guardar Pago</button>
</form>
