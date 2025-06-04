<?php

// Carga las variables de entorno desde el archivo .env
$host = $_ENV['DB_HOST'] ?? '';
$db = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

// Charset de la base de datos
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

// Data Source Name (DSN) para la conexión a la base de datos
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Intenta establecer la conexión a la base de datos utilizando PDO
try {
  $pdo = new PDO($dsn, $user, $pass);
  // echo "Conexión exitosa a la base de datos $db en $host";
} catch (PDOException $event) {
  echo "Error al conectar a la base de datos: " . $event->getMessage();
  exit; // Termina la ejecución si hay un error de conexión
}
