<main class="task-dashboard">
  <div class="task-dashboard__header">
    <h2 class="task-dashboard__headings"><?php echo $titulo; ?></h2>
    <p class="task-dashboard__text">Controla, crea y actualiza tus tareas</p>
  </div>

  <?php require_once __DIR__ . '/../templates/alertas.php'; ?>
  <?php
  // ==================================================================
  // DATOS PROVISIONALES PARA PRUEBAS (ELIMINAR DESPUÉS)
  // ==================================================================

  // Para probar cómo se ve la lista CON tareas, usa este bloque:
  $tasks = [
    (object)['nombre' => 'Aprender sobre controladores'],
    (object)['nombre' => 'Maquetar la vista de tareas'],
    (object)['nombre' => 'Conectar la base de datos']
  ];

  // Para probar cómo se ve SIN tareas, comenta el bloque de arriba
  // y descomenta la siguiente línea:
  // $tasks = [];

  // ==================================================================
  ?>
  <?php if (count($tasks) === 0): ?>
    <p class="task-dashboard__no-tasks">No hay Tareas aún, comienza creando uno</p>
  <?php else: ?>
    <ul class="task-list">
      <?php foreach ($tasks as $task): ?>
        <li class="task">
          <p class="task-name"><?php echo $task->nombre ?></p>
          <span class="task__description"></span>
          <div class="task__acciones">
            <button class="task__button task__button--editar">Editar</button>
            <button class="task__button task__button--eliminar">Eliminar</button>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</main>