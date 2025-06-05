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
  public $token;
  public $confirmado;

  // Constructor para inicializar las propiedades del usuario
  public function __construct(array $args = [])
  {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;
  }

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
}
