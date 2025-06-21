<?php
// iniciar sesión PHP
// session_start();

require_once __DIR__ . '/../app/includes/app.php'; // Incluye el archivo de configuración y funciones

use Controllers\AuthController;
use Controllers\TaskController;
use MVC\Router; // Importa la clase Router para manejar las rutas

// Crea una instancia del Router
$router = new Router();

// Login 
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Registro
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Olvidé mi contraseña
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Reestablecer contraseña
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmar cuenta
// Mensaje de confirmación
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

// Ruta para tareas
$router->get('/tareas', [TaskController::class, 'tareas']);

$router->comprobarRutas(); // Comprueba las rutas y ejecuta la función correspondiente
