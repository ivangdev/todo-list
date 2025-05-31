<?php

// 
require_once __DIR__ . '/../app/includes/app.php'; // Incluye el archivo de configuración y funciones

use MVC\Router; // Importa la clase Router para manejar las rutas

// Crea una instancia del Router
$router = new Router();

// Login 
$router->get('/login', [], 'login');
$router->post('/login', [], 'login');
$router->post('/login', [], 'logout');

$router->comprobarRutas(); // Comprueba las rutas y ejecuta la función correspondiente
