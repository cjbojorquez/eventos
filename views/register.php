<?php if (isset($_GET['error'])): ?>
    <p style="color: red;">Error al registrar el usuario. Intente nuevamente.</p>
<?php endif; ?>
<form action="routes.php" method="POST">
    <input type="hidden" name="action" value="register">
    <label for="username">Usuario:</label>
    <input type="text" name="username" required>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>
    <button type="submit">Registrar</button>
</form>
