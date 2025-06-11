<?php
// Autoload de Composer para cargar las dependencias automáticamente

use Dotenv\Dotenv; // Importa la clase Dotenv para manejar variables de entorno
use Models\ActiveRecord; // Importa la clase ActiveRecord para manejar la conexión a la base de datos y las operaciones CRUD

require __DIR__ . '/../../vendor/autoload.php';

// Si tu archivo .env está en el mismo directorio que este archivo (app/includes/.env)
$dotenv = Dotenv::createImmutable(__DIR__); // Crea una instancia de Dotenv para cargar las variables de entorno
$dotenv->safeLoad(); // Carga las variables de entorno desde el archivo .env

// Funciones auxiliares
require 'funciones.php';

require 'database.php'; // Incluye el archivo de configuración de la base de datos

ActiveRecord::setDB($db); // Establece la conexión a la base de datos en la clase ActiveRecord
