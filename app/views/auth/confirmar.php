<main class="auth">
  <div class="auth__contenedor">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Tu cuenta Todo List</p>

    <?php
    require_once __DIR__ . '/../templates/alertas.php';
    ?>

    <?php if (isset($alertas['success'])): ?>
      <div class="acciones--centrar">
        <a href="/login" class="acciones__enlace">Iniciar Sesión</a>

      <?php endif; ?>
      </div>
</main>