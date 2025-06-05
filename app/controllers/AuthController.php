<?php

namespace Controllers;

use Models\Usuario;
use MVC\Router;

class AuthController
{
  public static function login(Router $router)
  {
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    }

    $router->render('auth/login', [
      'titulo' => 'Iniciar Sesión',
      'alertas' => $alertas
    ]);
  }

  public static function registro(Router $router)
  {
    $alertas = [];
    $usuario = new Usuario();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    }

    $router->render('auth/registro', [
      'titulo' => 'Crear tu cuenta en Todo List',
      'alertas' => $alertas,
    ]);
  }
}
