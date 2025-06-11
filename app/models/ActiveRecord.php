<?php

namespace Models;

// Importamos las clases necesarias para manejar la base de datos
use PDO;
use PDOException;

class ActiveRecord
{
  // Protected se usa para que las propiedades sean accesibles en las clases hijas
  protected static $db;
  protected static $tabla = '';
  protected static $columnasDB = [];

  protected static $alertas = [];

  public ?int $id; // Propiedad para almacenar el ID del registro
  public $columnaDB; // Propiedad para almacenar las columnas de la base de datos


  // Traemos la conexión a la base de datos para usar en ActiveRecord
  public static function setDB($database)
  {
    self::$db = $database;
  }

  // Metodo para setear errores y mensajes de alerta
  public static function setAlertas(string $tipo, string $mensaje)
  {
    static::$alertas[$tipo][] = $mensaje;
  }

  // Metodo para obtener las alertas
  public static function getAlertas()
  {
    return static::$alertas;
  }

  // Metodo para limpiar las alertas
  public static function validar()
  {
    static::$alertas = [];
    return static::$alertas;
  }

  // Consulta SQL para crear un objeto a partir de un registro
  public static function consultarSQL(string $query)
  {
    try {
      // Consulta en la base de datos
      $stmt = self::$db->prepare($query);
      $stmt->execute();

      // Iteramos los resultados y creamos un objeto de ellos
      $array = [];
      while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $array[] = static::crearObjeto($registro); // Creamos un objeto a partir del registro
      }

      $stmt->closeCursor(); // Cerramos el cursor para liberar la conexión a la base de datos
      return $array; // Retornamos el array de objetos
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error en la base de datos: ' . $event->getMessage());
      return [];
    }
  }

  // Método para crear un objeto a partir de un registro
  /**
   * Método que convierte un registro de la base de datos (array asociativo) en un objeto del modelo.
   * Se utiliza cuando se recuperan datos de la base de datos y se necesitan convertir en objetos
   * para poder manipularlos con los métodos del modelo.
   * 
   * @param array $registro El registro de la base de datos a convertir en objeto
   * @return static Una nueva instancia del modelo con las propiedades establecidas
   */
  protected static function crearObjeto(array $registro)
  {
    $objeto = new static; // Creamos una nueva instancia de la clase que llama al método

    // Iteramos sobre el registro y asignamos los valores a las propiedades del objeto
    foreach ($registro as $key => $value) {
      if (property_exists($objeto, $key)) {
        $objeto->$key = $value; // Asignamos el valor al objeto si la propiedad existe
      }
    }
    return $objeto; // Retornamos el objeto creado
  }

  // Método para crear un array asociativo a partir de las propiedades del objeto
  /**
   * Método que convierte las propiedades del objeto en un array asociativo.
   * Se utiliza cuando se necesita preparar los datos del objeto para guardarlos en la base de datos,
   * excluyendo el ID ya que generalmente es autoincrementable y no debe modificarse.
   * 
   * @return array Array asociativo con los atributos del objeto listos para operaciones en BD
   */
  public function atributos()
  {
    $atributos = []; // Creamos un array para almacenar los atributos del objeto
    foreach (static::$columnasDB as $columna) {
      // Verificamos si la columna es 'id' para no incluirla en los atributos
      if ($columna === 'id') {
        continue; // Saltamos el ID ya que no se debe modificar
      }
      // Asignamos el valor de la propiedad del objeto al array de atributos
      $atributos[$columna] = $this->$columna;
    }
    return $atributos; // Retornamos los atributos del objeto
  }

  // Método para guardar un registro en la base de datos (CRUD)
  public function guardar()
  {
    $resultado = '';
    if (!is_null($this->id)) {
      // Si el ID no es nulo, significa que el registro ya existe y se debe actualizar
      $resultado = $this->actualizar();
    } else {
      // Si el ID es nulo, significa que es un nuevo registro y se debe insertar
      $resultado = $this->crear();
    }
    return $resultado;
  }

  // Metodo para obtener todos los registros de la tabla
  public static function all($orden = 'DESC')
  {
    $query = "SELECT * FROM " . static::$tabla . " ORDER BY id {$orden}";
    $resultado = self::consultarSQL($query); // Ejecutamos la consulta SQL
    return $resultado;
  }

  // Método para obtener un registro por su ID
  public static function find(int $id)
  {
    try {
      $query = "SELECT * FROM " . static::$tabla . " WHERE id = :id LIMIT 1";
      $stmt = self::$db->prepare($query);
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
      $datosParaEjecutar = ['id' => $id];
      $resultado = $stmt->execute($datosParaEjecutar);

      debuguear($resultado);

      if ($resultado) {
        return [
          'resultado' => true,
          'registro' => $resultado,
        ];
      } else {
        return [
          'resultado' => false,
          'registro' => null
        ];
      }
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error de base de datos al buscar por ID: ' . $event->getMessage());
      return null; // Si ocurre un error, retornamos null
    }
  }

  // Método para realizar una búsqueda con una condición específica en una columna
  public static function where(string $columna, $valor)
  {
    try {
      if (!in_array($columna, static::$columnasDB)) {
        throw new \Exception("Columna '{$columna}' no válida en la tabla " . static::$tabla);
      }

      $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = :valor LIMIT 1";
      $stmt = self::$db->prepare($query);
      $datosParaEjecutar = ['valor' => $valor];
      $stmt->execute($datosParaEjecutar);
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($resultado) {
        return static::crearObjeto($resultado); // Creamos un objeto a partir del resultado
      }
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error de base de datos al buscar por columna: ' . $event->getMessage());
      return false; // Si ocurre un error, retornamos null
    }
  }

  public function crear()
  {
    $atributos = $this->atributos();

    $columnas = join(', ', array_keys($atributos)); // Creamos una cadena con los nombres de las columnas para la consulta SQL

    $placeholders = ':' . join(', :', array_keys($atributos)); // Creamos una cadena con los placeholders para los valores

    $query = "INSERT INTO " . static::$tabla . " ({$columnas}) VALUES ({$placeholders})"; // Creamos la consulta SQL para insertar un nuevo registro

    try {
      // Preparamos la consulta SQL
      $stmt = self::$db->prepare($query);

      $datosParaEjecutar = [];
      foreach ($atributos as $columna => $value) {
        $datosParaEjecutar[':' . $columna] = $value; // Asignamos los valores a los placeholders
      }

      $resultado = $stmt->execute($datosParaEjecutar);
      // debuguear($resultado);
      if ($resultado) {
        $this->id = self::$db->lastInsertId();
        return [
          'resultado' => true,
          'id' => $this->id
        ];
      } else {
        static::setAlertas('error', 'Error al crear el registro en la base de datos.');
        return [
          'resultado' => false,
          'id' => null
        ];
      }
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error de base de datos al crear: ' . $event->getMessage());
      return [
        'resultado' => false,
        'id' => null
      ];
    }
  }

  public function actualizar()
  {
    $atributos = $this->atributos();
    $paresSetSql = []; // Array para almacenar los pares columna = valor para la consulta SQL

    foreach (array_keys($atributos) as $key) {
      $paresSetSql[] = "{$key} = :{$key}"; // Creamos los pares columna = valor para la consulta SQL
    }

    $query = "UPDATE " . static::$tabla . " SET " . join(', ', $paresSetSql);
    $query .= " WHERE id = :id_where"; // placeholder para el ID del registro a actualizar

    try {
      $stmt = self::$db->prepare($query);

      $datosParaEjecutar = [];
      foreach ($atributos as $columna => $value) {
        $datosParaEjecutar[':' . $columna] = $value; // Asignamos los valores a los placeholders
      }

      $datosParaEjecutar[':id_where'] = $this->id; // Asignamos el ID del registro a actualizar

      $resultado = $stmt->execute($datosParaEjecutar); // Ejecutamos la consulta SQL

      if ($resultado) {
        $filasAfectadas = $stmt->rowCount(); // Obtenemos el número de filas afectadas por la actualización
        if ($filasAfectadas > 0) {
          return true; // Si se actualizaron filas, retornamos true
        } else {
          static::setAlertas('error', 'No se encontraron registros para actualizar.');
          return false; // Si no se actualizaron filas, retornamos false
        }
      }
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error de base de datos al actualizar: ' . $event->getMessage());
      return false; // Si ocurre un error, retornamos false
    }
  }

  // Método para sincronizar el objeto con la base de datos
  public function sincronizar(array $args = [])
  {
    foreach ($args as $key => $value) {
      if (property_exists($this, $key) && !is_null($value)) {
        $this->$key = $value;
      }
    }
  }

  public function eliminar()
  {
    if (is_null($this->id) || !is_numeric($this->id)) {
      static::setAlertas('error', 'ID no válido para eliminar el registro.');
      return false; // Si el ID no es válido, retornamos false
    }

    $query = "DELETE FROM " . static::$tabla . " WHERE id = :id_where LIMIT 1"; // Consulta SQL para eliminar un registro por ID

    try {
      $stmt = self::$db->prepare($query);

      $datosParaEjecutar = [':id_where' => $this->id]; // Asignamos el ID del registro a eliminar
      $resultado = $stmt->execute($datosParaEjecutar); // Ejecutamos la consulta SQL

      if ($resultado) {
        return true;
      } else {
        static::setAlertas('error', 'Error al eliminar el registro en la base de datos.');
        return false; // Si ocurre un error, retornamos false
      }
    } catch (PDOException $event) {
      static::setAlertas('error', 'Error de base de datos al eliminar: ' . $event->getMessage());
      return false; // Si ocurre un error, retornamos false
    }
  }
}
