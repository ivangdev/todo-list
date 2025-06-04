<?php

// 
require_once __DIR__ . '/../app/includes/app.php'; // Incluye el archivo de configuración y funciones

use Controllers\AuthController;
use MVC\Router; // Importa la clase Router para manejar las rutas

// Crea una instancia del Router
$router = new Router();

// Login 
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'logout']);

$router->comprobarRutas(); // Comprueba las rutas y ejecuta la función correspondiente
