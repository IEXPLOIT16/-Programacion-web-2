<?php
// ================================================
// resenas.php - Sistema de Reseñas y Calificaciones
// Programación Web II - Actividad 1
// Alumno: Claudio Baeza Henríquez
// ================================================

// Se inicia la sesión para guardar las reseñas mientras el servidor está activo
session_start();

// Se inicializa el arreglo de reseñas si aún no existe en la sesión
if (!isset($_SESSION['resenas'])) {
    $_SESSION['resenas'] = [];
}

// Variable para guardar mensajes de resultado
$mensaje     = "";
$tipo_mensaje = "";

// -------------------------------------------------------
// ACTIVIDAD 1: Función PHP para registrar reseñas
// Se ejecuta cuando el usuario envía el formulario
// -------------------------------------------------------

/**
 * Función que valida y registra una reseña de producto.
 * Recibe los datos del formulario, los verifica y los
 * guarda en la sesión para mostrarlos en la página.
 *
 * @param string $producto    Nombre del producto calificado
 * @param string $nombre      Nombre del usuario que escribe la reseña
 * @param int    $calificacion Puntaje del 1 al 5
 * @param string $comentario  Texto de la reseña
 * @return array              Arreglo con 'exito' (bool) y 'mensaje' (string)
 */
function registrarResena($producto, $nombre, $calificacion, $comentario) {

    // Validar que todos los campos estén completos
    if (empty($producto) || empty($nombre) || empty($comentario)) {
        return [
            'exito'   => false,
            'mensaje' => "Error: todos los campos son obligatorios."
        ];
    }

    // Validar que la calificación esté entre 1 y 5
    if ($calificacion < 1 || $calificacion > 5) {
        return [
            'exito'   => false,
            'mensaje' => "Error: la calificación debe estar entre 1 y 5."
        ];
    }

    // Validar que el comentario tenga al menos 10 caracteres
    if (strlen($comentario) < 10) {
        return [
            'exito'   => false,
            'mensaje' => "Error: el comentario debe tener al menos 10 caracteres."
        ];
    }

    // Crear la reseña como un arreglo asociativo
    $nueva_resena = [
        'producto'     => htmlspecialchars($producto),
        'nombre'       => htmlspecialchars($nombre),
        'calificacion' => intval($calificacion),
        'comentario'   => htmlspecialchars($comentario),
        'fecha'        => date("d/m/Y H:i")
    ];

    // Guardar la reseña en la sesión
    $_SESSION['resenas'][] = $nueva_resena;

    return [
        'exito'   => true,
        'mensaje' => "¡Reseña registrada correctamente! Gracias, " . htmlspecialchars($nombre) . "."
    ];
}

// Procesar el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = registrarResena(
        $_POST['producto']     ?? '',
        $_POST['nombre']       ?? '',
        $_POST['calificacion'] ?? 0,
        $_POST['comentario']   ?? ''
    );

    $mensaje      = $resultado['mensaje'];
    $tipo_mensaje = $resultado['exito'] ? 'exito' : 'error';
}

// Función auxiliar: genera estrellas según la calificación
function mostrarEstrellas($cantidad) {
    $estrellas = "";
    for ($i = 1; $i <= 5; $i++) {
        $estrellas .= ($i <= $cantidad) ? "★" : "☆";
    }
    return $estrellas;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Reseñas de Productos - Tienda Online</title>
</head>
<body>

  <!-- Encabezado -->
  <header>
    <h1>🛒 Mi Tienda Online</h1>
    <nav class="header-nav">
      <a href="index.php" class="nav-link">🏠 Inicio</a>
      <a href="formulario_pedido.php" class="nav-link">📦 Hacer pedido</a>
    </nav>
  </header>

  <main>
    <div class="pagina-contenido">

      <!-- Banner de la página -->
      <div class="pagina-banner">
        <h2>⭐ Reseñas y Calificaciones</h2>
        <p>Comparte tu experiencia con los productos que has comprado. Tu opinión ayuda a otros compradores.</p>
      </div>

      <!-- Mensaje de resultado -->
      <?php if (!empty($mensaje)): ?>
        <div class="alerta <?php echo $tipo_mensaje; ?>">
          <?php echo $mensaje; ?>
        </div>
      <?php endif; ?>

      <!-- Formulario de reseña -->
      <div class="form-card">
        <h3>Escribir una reseña</h3>
        <form method="POST" action="resenas.php">

          <div class="campo">
            <label for="producto">Producto comprado:</label>
            <select id="producto" name="producto" required>
              <option value="">-- Selecciona un producto --</option>
              <option value="Laptop HP 15">Laptop HP 15</option>
              <option value="Laptop Lenovo IdeaPad">Laptop Lenovo IdeaPad</option>
              <option value="MacBook Air M2">MacBook Air M2</option>
              <option value="iPhone 14">iPhone 14</option>
              <option value="Samsung Galaxy A54">Samsung Galaxy A54</option>
              <option value="Xiaomi Redmi Note 12">Xiaomi Redmi Note 12</option>
              <option value="Mouse inalámbrico">Mouse inalámbrico</option>
              <option value="Teclado USB">Teclado USB</option>
              <option value="Audífonos Bluetooth">Audífonos Bluetooth</option>
            </select>
          </div>

          <div class="campo">
            <label for="nombre">Tu nombre:</label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Ej: Juan Pérez" required>
          </div>

          <div class="campo">
            <label for="calificacion">Calificación:</label>
            <select id="calificacion" name="calificacion" required>
              <option value="">-- Selecciona --</option>
              <option value="5">★★★★★ Excelente (5)</option>
              <option value="4">★★★★☆ Muy bueno (4)</option>
              <option value="3">★★★☆☆ Bueno (3)</option>
              <option value="2">★★☆☆☆ Regular (2)</option>
              <option value="1">★☆☆☆☆ Malo (1)</option>
            </select>
          </div>

          <div class="campo">
            <label for="comentario">Comentario:</label>
            <textarea id="comentario" name="comentario" rows="4"
                      placeholder="Escribe tu experiencia con el producto..."
                      required></textarea>
          </div>

          <button type="submit" class="btn-enviar">Enviar reseña</button>

        </form>
      </div>

      <!-- Mostrar reseñas registradas -->
      <div class="resenas-lista">
        <h3>Reseñas registradas (<?php echo count($_SESSION['resenas']); ?>)</h3>

        <?php if (empty($_SESSION['resenas'])): ?>
          <p class="sin-resenas">Aún no hay reseñas. ¡Sé el primero en opinar!</p>

        <?php else: ?>
          <!-- Se recorre el arreglo de reseñas guardadas en sesión -->
          <?php foreach (array_reverse($_SESSION['resenas']) as $resena): ?>
            <div class="resena-card">
              <div class="resena-header">
                <span class="resena-producto"><?php echo $resena['producto']; ?></span>
                <span class="resena-estrellas"><?php echo mostrarEstrellas($resena['calificacion']); ?></span>
              </div>
              <p class="resena-comentario">"<?php echo $resena['comentario']; ?>"</p>
              <div class="resena-footer">
                <span class="resena-autor">— <?php echo $resena['nombre']; ?></span>
                <span class="resena-fecha"><?php echo $resena['fecha']; ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>Desarrollado por <strong>Claudio Baeza Henríquez</strong> &mdash; Programación Web II</p>
  </footer>

</body>
</html>
