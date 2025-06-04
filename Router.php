<?php

namespace MVC;

class Router
{

  public array $getRoutes = []; // Get para rutas
  public array $postRoutes = []; // Post para rutas

  // Métodos estáticos para acceder a las rutas
  public function get($url, $fn)
  {
    $this->getRoutes[$url] = $fn;
  }

  // Método para registrar rutas POST
  public function post($url, $fn)
  {
    $this->postRoutes[$url] = $fn;
  }

  // Método para comprobar las rutas y ejecutar la función correspondiente
  public function comprobarRutas(): void
  {
    $url_actual = $_SERVER['PATH_INFO'] ?? '/'; // Obtiene la URL actual, o '/' si no está definida
    $method = $_SERVER['REQUEST_METHOD']; // Obtiene el método de la solicitud (GET o POST)

    if ($method === "GET") {
      $fn = $this->getRoutes[$url_actual] ?? null; // Busca la función asociada a la ruta GET
    } else {
      $fn = $this->postRoutes[$url_actual] ?? null; // Busca la función asociada a la ruta POST
    }

    if ($fn) {
      call_user_func($fn, $this); // Llama a la función asociada a la ruta, pasando el objeto Router como argumento
    } else {
      echo "Ruta no encontrada"; // Si no se encuentra la ruta, muestra un mensaje de error
    }
  }

  // Método para renderizar vistas
  public function render($view, $datos = [])
  {
    // Los datos se extraen para que estén disponibles como variables en la vista
    foreach ($datos as $key => $value) {
      $$key = $value; // Extrae cada clave del array $datos como una variable
    }

    ob_start(); // Inicia el almacenamiento en búfer de salida

    // Incluye el archivo de la vista.
    // __DIR__ es el directorio del archivo actual (Router.php).
    // Se asume que las vistas están en un directorio 'views' al mismo nivel que el directorio 'MVC'
    // Ejemplo: si Router.php está en 'app/MVC/Router.php', las vistas estarían en 'app/views/'
    include_once __DIR__ . "/app/views/$view.php";

    $contenido = ob_get_clean(); // Obtiene el contenido del búfer (la vista renderizada) y lo limpia

    // Incluye el layout principal. La variable $contenido estará disponible dentro de layout.php
    // Se asume que layout.php está en el mismo directorio de vistas.
    include_once __DIR__ . '/app/views/layout.php';
  }
}
