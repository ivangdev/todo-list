<?php

namespace Models;

class Usuario extends ActiveRecord
{
  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

  // Propiedades del usuario
  public ?int $id;
  public string $nombre;
  public string $email;
  public string $password;
  public string $password2;
  public $token;
  public $confirmado;
  public string $password_actual;
  public string $password_nuevo;

  // Constructor para inicializar las propiedades del usuario
  public function __construct(array $args = [])
  {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->password2 = $args['password2'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;
  }

  // Método para validar el Login del usuario
  public function validarLogin(): array
  {
    if (!$this->email) {
      self::$alertas['error'][] = 'El Email es obligatorio';
    }
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = 'El Email no es válido';
    }
    if (!$this->password) {
      self::$alertas['error'][] = 'El Password es obligatorio';
    }
    return self::$alertas;
  }

  // Método para validar el registro del usuario
  public function validar_cuenta(): array
  {
    if (!$this->nombre) {
      self::$alertas['error'][] = 'El nombre es obligatorio';
    }
    if (!$this->email) {
      self::$alertas['error'][] = 'El Email es obligatorio';
    }
    if (!$this->password) {
      self::$alertas['error'][] = 'El Password es obligatorio';
    }
    if (strlen($this->password) < 6) {
      self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
    }
    if ($this->password !== $this->password2) {
      self::$alertas['error'][] = 'Los Passwords no coinciden';
    }
    return self::$alertas;
  }

  // Método para validar el email del usuario
  public function validarEmail(): array
  {
    if (!$this->email) {
      self::$alertas['error'][] = 'El Email es obligatorio';
    }
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = 'El Email no es válido';
    }
    return self::$alertas;
  }

  // Método para validar el password del usuario
  public function validarPassword(): array
  {
    if (!$this->password) {
      self::$alertas['error'][] = 'El Password es obligatorio';
    } else {
      if (strlen($this->password) < 6) {
        self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
      }
    }
    return self::$alertas;
  }

  // Método para nuevo password
  public function nuevoPassword(): array
  {
    if (!$this->password_actual) {
      self::$alertas['error'][] = 'El Password actual es obligatorio';
    }
    if (!$this->password_nuevo) {
      self::$alertas['error'][] = 'El nuevo Password es obligatorio';
    }
    if (strlen($this->password_nuevo) < 6) {
      self::$alertas['error'][] = 'El nuevo Password debe tener al menos 6 caracteres';
    }
    return self::$alertas;
  }

  // Método para hashear el password antes de guardarlo en la base de datos
  public function hashPasword(): void
  {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  // Método para generar un token único para el usuario
  public function generarToken(): void
  {
    $this->token = uniqid();
  }
}
