<?php

namespace Controllers;

use Models\Tarea;
use MVC\Router;

class TaskController
{
  // Pagina donde se muestran las tareas del usuario
  public static function tareas(Router $router)
  {

    $router->render('tasks/index', [
      'titulo' => 'Mis Tareas'
    ]);
  }

  public static function crear(Router $router)
  {
    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION['login']) !== true) {
      header('Location: /login');
    }
    $alertas = [];
    $tarea = new Tarea;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $tarea->sincronizar($_POST);
      $alertas = $tarea->validar();

      if (empty($alertas)) {
        $tarea->usuario_id = $_SESSION['id'];
        $resultado = $tarea->guardar();

        if ($resultado) {
          $respuesta = [
            'tipo' => 'exito',
            'id' => $resultado['id'],
            'mensaje' => 'Tarea creada correctamente',
            'Nombre' => $tarea->nombre
          ];
          echo json_encode($respuesta);
          return;
        }
      } else {
        $respuesta = [
          'tipo' => 'error',
          'mensaje' => 'Error al crear la tarea',
          'alertas' => $alertas
        ];
        echo json_encode($respuesta);
        return;
      }
    }

    $router->render('tasks/crear', [
      'titulo' => 'Crear Tarea',
      'alertas' => $alertas,
      'tarea' => $tarea
    ]);
  }
  public static function actualizar() {}
  public static function eliminar() {}
}
