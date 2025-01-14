<?php
include 'auth/auth.php'; 
require '../db.php';

// Inicializar variables
$id = $nombre = $apellido = $email = $telefono = $dpi = $fecha_nacimiento = $usuario = $responsable = $parentesco = $telefono_responsable = $talla = '';
$edit_mode = false;

// Variables para fecha de nacimiento
$dia_nac = $mes_nac = $anio_nac = '';

// Verificar si es edición
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM participante WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $participante = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($participante);
    if ($participante) {
        extract($participante);
        if ($fecha_nacimiento) {
            list($anio_nac, $mes_nac, $dia_nac) = explode('-', $fecha_nacimiento);
        }
        $edit_mode = true;
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $dpi = $_POST['dpi'];
    $usuario = $_SESSION['nombre_usuario'];
    $talla = $_POST['talla'];
    $responsable = $_POST['responsable'];
    $parentesco = $_POST['parentesco'];
    $telefono_responsable = $_POST['telefono_responsable'];
    $id_evento = $_SESSION['id_evento'];
    $estado = 1;
    $creado_en = date('Y-m-d H:i:s'); // Fecha actual

    // Fecha de nacimiento
    $dia_nac = $_POST['dia_nac'];
    $mes_nac = $_POST['mes_nac'];
    $anio_nac = $_POST['anio_nac'];
    $fecha_nacimiento = "$anio_nac-$mes_nac-$dia_nac";

    if ($edit_mode) {
        // Actualizar participante
        if($usuario=='admin' or $usuario=='joel'){
            $query = "UPDATE participante SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, dpi = :dpi, fecha_nacimiento = :fecha_nacimiento, talla=:talla,responsable = :responsable, parentesco = :parentesco, telefono_responsable = :telefono_responsable WHERE id = :id";    
        }else{
            $query = "UPDATE participante SET  email = :email, telefono = :telefono, dpi = :dpi, fecha_nacimiento = :fecha_nacimiento, talla=:talla, responsable = :responsable, parentesco = :parentesco, telefono_responsable = :telefono_responsable WHERE id = :id";
        }
    } else {
        // Crear nuevo participante
        $query = "INSERT INTO participante (nombre, apellido, email, telefono, dpi, fecha_nacimiento, talla, usuario, responsable, parentesco, telefono_responsable,estado,creado_en,id_evento) 
                  VALUES (:nombre, :apellido, :email, :telefono, :dpi, :fecha_nacimiento, :talla, :usuario, :responsable, :parentesco, :telefono_responsable,:estado,:creado_en,:id_evento)";
    }

    $stmt = $pdo->prepare($query);

    try {
        
        if ($edit_mode) {
            if($usuario=='admin' or $usuario=='joel'){
                $params = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dpi' => $dpi,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'talla' => $talla,
                    'responsable' => $responsable,
                    'parentesco' => $parentesco,
                    'telefono_responsable' => $telefono_responsable,
                    'id'=> $id
                ];
            }else{
                $params = [
                    'email' => $email,
                    'telefono' => $telefono,
                    'dpi' => $dpi,
                    'fecha_nacimiento' => $fecha_nacimiento,  
                    'talla' => $talla,                
                    'responsable' => $responsable,
                    'parentesco' => $parentesco,
                    'telefono_responsable' => $telefono_responsable,
                    'id'=> $id
                ];
            }
        }else{
            $params = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'dpi' => $dpi,
                'fecha_nacimiento' => $fecha_nacimiento,
                'talla' => $talla,
                'usuario' => $usuario,
                'responsable' => $responsable,
                'parentesco' => $parentesco,
                'telefono_responsable' => $telefono_responsable,
                'id_evento' => $id_evento,
                'estado' => $estado,
                'creado_en' => $creado_en
            ];
        }
        $stmt->execute($params);
        $success_message = $edit_mode ? "Participante actualizado exitosamente." : "Participante creado exitosamente.";
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
    <title><?php echo $edit_mode ? 'Editar' : 'Crear'; ?> Participante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<div class="d-flex" id="wrapper">
    <?php include 'partials/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <?php include 'partials/navbar.php'; ?>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo $edit_mode ? 'Editar' : 'Crear'; ?> Participante</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success"><?php echo $success_message; ?></div>
                            <?php elseif (isset($error_message)): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telefono</label>
                                    <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">DPI</label>
                                    <input type="text" class="form-control" name="dpi" value="<?php echo htmlspecialchars($dpi); ?>" >
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <div class="d-flex gap-2">
                                        <select class="form-select" name="dia_nac">
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($dia_nac == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select class="form-select" name="mes_nac">
                                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($mes_nac == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select class="form-select" name="anio_nac">
                                            <?php for ($i = 1980; $i <= 2014; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($anio_nac == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="talla" class="form-label">Talla</label>
                                    <select class="form-select" id="talla" name="talla" required>
                                        <option value="XS" <?php echo ($talla == 'XS') ? 'selected' : ''; ?>>XS</option>
                                        <option value="S" <?php echo ($talla == 'S') ? 'selected' : ''; ?>>S</option>
                                        <option value="M" <?php echo ($talla == 'M') ? 'selected' : ''; ?>>M</option>
                                        <option value="L" <?php echo ($talla == 'L') ? 'selected' : ''; ?>>L</option>
                                        <option value="XL" <?php echo ($talla == 'XL') ? 'selected' : ''; ?>>XL</option>
                                        
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Responsable</label>
                                    <input type="text" class="form-control" name="responsable" value="<?php echo htmlspecialchars($responsable); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="parentesco" class="form-label">Parentesco</label>
                                    <select class="form-select" id="parentesco" name="parentesco" required>
                                        <option value="Padre" <?php echo ($parentesco == 'padre') ? 'selected' : ''; ?>>Padre</option>
                                        <option value="Madre" <?php echo ($parentesco == 'madre') ? 'selected' : ''; ?>>Madre</option>
                                        <option value="Abuelo" <?php echo ($parentesco == 'abuelo') ? 'selected' : ''; ?>>Abuelo</option>
                                        <option value="Tio" <?php echo ($parentesco == 'tio') ? 'selected' : ''; ?>>Tío</option>
                                        <option value="Hermano" <?php echo ($parentesco == 'hermano') ? 'selected' : ''; ?>>Abuelo</option>
                                        <option value="Otro" <?php echo ($parentesco == 'otro') ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telefono Responsable</label>
                                    <input type="text" class="form-control" name="telefono_responsable" value="<?php echo htmlspecialchars($telefono_responsable); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Actualizar' : 'Crear'; ?></button>
                            </form>
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
