<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@200,300,400,500,600,700&display=swap" rel="stylesheet">
  <title>Todo List - <?php echo $titulo; ?></title>
  <link rel="stylesheet" href="/dist/css/app.css">
</head>

<body>
  <?php
  include_once __DIR__ . '/layout.php';
  echo $contenido;
  include_once __DIR__ . '/templates/footer.php';
  ?>
  <script src="/dist/js/bundle.min.js" defer></script>
</body>

</html>