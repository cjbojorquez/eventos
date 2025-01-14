<?php
require '../db.php'; // Conexión a la base de datos

// Verificar si el ID del participante se envió como parámetro
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Obtener el estado actual del participante
        $stmt = $pdo->prepare("SELECT estado FROM participante WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $participante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($participante) {
            $estado_actual = $participante['estado'];

            // Determinar el nuevo estado
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1;

            // Actualizar el estado del participante
            $stmt = $pdo->prepare("UPDATE participante SET estado = :nuevo_estado WHERE id = :id");
            $stmt->execute([
                'nuevo_estado' => $nuevo_estado,
                'id' => $id
            ]);

            // Respuesta en formato JSON
            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente.',
                'nuevo_estado' => $nuevo_estado
            ]);
        } else {
            // Participante no encontrado
            echo json_encode([
                'success' => false,
                'message' => 'Participante no encontrado.'
            ]);
        }
    } catch (PDOException $e) {
        // Manejo de errores
        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ]);
    }
} else {
    // ID no proporcionado
    echo json_encode([
        'success' => false,
        'message' => 'ID del participante no proporcionado.'
    ]);
}
?>
