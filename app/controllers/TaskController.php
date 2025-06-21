<?php

namespace Controllers;

use MVC\Router;

class TaskController
{
  public static function tareas(Router $router)
  {

    $router->render('tasks/index', [
      'titulo' => 'Mis Tareas'
    ]);
  }
  public static function create() {}
  public static function update() {}
  public static function delete() {}
}
