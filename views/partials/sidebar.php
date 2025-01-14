<script src="https://kit.fontawesome.com/a1fe5c837b.js" crossorigin="anonymous"></script>


<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
      <span class="fs-4">Campa 2025</span>
    </a>
    <?php if(isset($_SESSION['nombre_usuario'])) {?>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="dashboard.php" class="nav-link active" aria-current="page">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"/></svg><i class="fa-solid fa-gauge"></i>
          Dashboard
        </a>
      </li>
      <hr>
      <li>
        <a href="crear_usuario.php" class="nav-link text-white">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg><i class="fa-solid fa-user-plus"></i>
          Crear Usuario
        </a>
      </li>
      <li>
        <a href="cambiar_contrasena.php" class="nav-link text-white">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"/></svg><i class="fa-solid fa-unlock-keyhole"></i>
          Cambiar Contraseña
        </a>
      </li>
      <hr>
      <li>
        <a href="participante_form.php" class="nav-link text-white">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"/></svg><i class="fa-solid fa-person-circle-plus"></i>
          Agregar
        </a>
      </li>
      <li>
        <a href="listar_participantes.php" class="nav-link text-white">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg><i class="fa-solid fa-rectangle-list"></i>
          Inscritos
        </a>
      </li>
    </ul>
    <hr>
    <?php } ?>
  </div>
