<?php

namespace Controllers;

use Classes\Email;
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
      $usuario->sincronizar($_POST);
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

          // debuguear($usuario);
          // Crear el usuario
          $resultado = $usuario->guardar();

          // Email de confirmacion
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarConfirmacion();

          if ($resultado) {
            header('Location: /mensaje');
          }
        }
      }
    }

    // Renderizar la vista de registro
    $router->render('auth/registro', [
      'titulo' => 'Crea tu cuenta en Todo List',
      'alertas' => $alertas,
    ]);
  }

  // Metodo para confirmar la cuenta del usuario
  public static function confirmar(Router $router)
  {
    // Obtener el token de la URL
    $token = htmlspecialchars($_GET['token']);

    // Redirigir si no hay token
    if (!$token) {
      header('Location: /');
    }

    // Buscar el usuario por el token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      // No existe el usuario con ese token
      Usuario::setAlertas('error', 'Token no válido, la cuenta no se confirmó');
    } else {
      // Confirmar el usuario
      $usuario->confirmado = 1;
      $usuario->token = '';
      unset($usuario->password2);

      // Guardar el usuario
      $usuario->guardar();

      Usuario::setAlertas('success', 'Cuenta confirmada correctamente');
    }

    // Renderizar la vista de confirmación
    $router->render('auth/confirmar', [
      'titulo' => 'Confirma tu cuenta en Todo List',
      'alertas' => Usuario::getAlertas()
    ]);
  }

  // Método mensaje para mostrar un mensaje después de crear la cuenta
  public static function mensaje(Router $router)
  {
    $router->render('auth/mensaje', [

      'titulo' => 'Cuenta creada correctamente'
    ]);
  }
}
