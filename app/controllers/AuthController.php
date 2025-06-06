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
      if (empty($alertas)) {
        $existeUsuario = Usuario::where('email', $usuario->email);
      }
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
      $alertas = $usuario->validar_cuenta();

      if (empty($alertas)) {
        $existeUsuario = Usuario::where('email', $usuario->email);
        if ($existeUsuario) {
          Usuario::setAlertas('error', 'El usuario ya está registrado');
          $alertas = Usuario::getAlertas();
        } else {
          // Hashear el password
          $usuario->hashPasword();

          // Eliminar password2
          unset($usuario->password2);

          // Generar un token
          $usuario->generarToken();

          // Crear el usuario
          $resultado = $usuario->guardar();

          // Email de confirmacion
        }
      }
    }

    $router->render('auth/registro', [
      'titulo' => 'Crea tu cuenta en Todo List',
      'alertas' => $alertas,
    ]);
  }
}
