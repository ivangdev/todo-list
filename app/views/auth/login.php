<main class="auth">
  <div class="auth__contenedor">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Ingresa a tu cuenta</p>

    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario" action="/login" method="POST">
      <div class="formulario__campo">
        <label for="email" class="formulario__label">Email</label>
        <input type="email" class="formulario__input" placeholder="Tu Email" id="email" name="email" />
      </div>

      <div class="formulario__campo">
        <label for="password" class="formulario__label">Password</label>
        <input type="password" class="formulario__input" placeholder="Tu Password" id="password" name="password" />
      </div>

      <input type="submit" class="formulario__submit" value="Iniciar Sesión" />
    </form>

    <div class="acciones">
      <a href="/registro" class="acciones__enlace">¿Aún no tenés cuenta? Obtener una</a>
      <a href="/olvide" class="acciones__enlace">¿Olvisate tu Password?</a>
    </div>
  </div>
</main>