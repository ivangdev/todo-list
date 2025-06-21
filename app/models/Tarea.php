<?php

namespace Models;

class Tarea extends ActiveRecord
{
  protected static $tabla = 'tareas';
  protected static $columnasDB = ['id', 'descriprion', 'estado', 'created_at', 'usuario_id'];

  // Propiedades de la tarea
  public ?int $id;
  public string $nombre;
  public string $descripcion;
  public int $estado;
  public ?\DateTime $created_at;
  public ?int $usuario_id;

  // Constructor para inicializar las propiedades de la tarea
  public function __construct(array $args = [])
  {
    $this->id = $args['id'] ?? null;
    $this->descripcion = $args['descripcion'] ?? '';
    $this->estado = $args['estado'] ?? 0;
    $this->created_at = isset($args['created_at']) ? new \DateTime($args['created_at']) : null;
    $this->usuario_id = $args['usuario_id'] ?? null;
  }
}
