<?php
// ================================================
// procesar_pedido.php - Recuperación y paso de variables
// Programación Web II - Actividad 4
// Alumno: Claudio Baeza Henríquez
// ================================================

// Se incluye la clase Pedido
require_once "clase_pedido.php";

// Se inicia la sesión para guardar y acceder a los pedidos
session_start();

// Se inicializa el arreglo de pedidos si no existe
if (!isset($_SESSION['pedidos'])) {
    $_SESSION['pedidos'] = [];
}

// Sanear la sesión: convertir cualquier objeto Pedido a arreglo
foreach ($_SESSION['pedidos'] as $indice => $item) {
    if (is_object($item) && method_exists($item, 'toArray')) {
        $_SESSION['pedidos'][$indice] = $item->toArray();
    }
}

// -------------------------------------------------------
// ACTIVIDAD 4: Recuperación de datos desde el formulario
//
// PHP ofrece dos métodos principales para recibir datos:
//
// $_POST  → recibe datos enviados con method="POST"
//           Los datos NO aparecen en la URL
//           Usado para formularios con datos sensibles
//
// $_GET   → recibe datos enviados con method="GET"
//           Los datos SÍ aparecen en la URL (?clave=valor)
//           Usado para búsquedas y filtros
//
// En este script se usa $_POST para recibir el formulario
// y $_GET para recibir el término de búsqueda de pedidos
// -------------------------------------------------------

$pedido_guardado = null;
$error           = "";

// ---- Paso 1: Recuperar datos del formulario via POST ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se recuperan las variables enviadas desde el formulario
    // usando el superglobal $_POST con el nombre de cada campo
    $descripcion   = isset($_POST['descripcion'])   ? trim($_POST['descripcion'])   : '';
    $tipoPedido    = isset($_POST['tipoPedido'])     ? trim($_POST['tipoPedido'])     : '';
    $producto      = isset($_POST['producto'])       ? trim($_POST['producto'])       : '';
    $unidades      = isset($_POST['unidades'])       ? intval($_POST['unidades'])     : 0;
    $observaciones = isset($_POST['observaciones'])  ? trim($_POST['observaciones'])  : '';

    // ---- Paso 2: Validar los datos recibidos ----
    if (empty($descripcion) || empty($tipoPedido) || empty($producto) || $unidades < 1) {
        $error = "Error: todos los campos obligatorios deben estar completos.";

    } else {

        // ---- Paso 3: Crear un objeto Pedido con los datos recibidos ----
        // Se instancia la clase Pedido pasando las variables recuperadas
        $pedido_guardado = new Pedido(
            htmlspecialchars($descripcion),
            htmlspecialchars($tipoPedido),
            htmlspecialchars($producto),
            $unidades,
            htmlspecialchars($observaciones)
        );

        // ---- Paso 4: Guardar el pedido en la sesión ----
        // Se convierte el objeto a arreglo para almacenarlo
        $_SESSION['pedidos'][] = $pedido_guardado->toArray();
    }
}

// ---- Búsqueda de pedidos via GET ----
// El término de búsqueda llega por la URL: ?buscar=laptop
$termino_busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$pedidos_filtrados = [];

if (!empty($termino_busqueda) && !empty($_SESSION['pedidos'])) {
    // Se usa el método estático de la clase para filtrar
    $pedidos_filtrados = Pedido::filtrarPorProducto($_SESSION['pedidos'], $termino_busqueda);
} else {
    $pedidos_filtrados = $_SESSION['pedidos'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Pedido Registrado - Tienda Online</title>
</head>
<body>

  <!-- Encabezado -->
  <header>
    <h1>🛒 Mi Tienda Online</h1>
    <nav class="header-nav">
      <a href="index.php" class="nav-link">🏠 Inicio</a>
      <a href="formulario_pedido.php" class="nav-link">📦 Nuevo pedido</a>
      <a href="resenas.php" class="nav-link">⭐ Reseñas</a>
    </nav>
  </header>

  <main>
    <div class="pagina-contenido">
      <!-- Banner de la página -->
      <div class="pagina-banner">
        <h2>✅ Resultado del Pedido</h2>
        <p>Aquí puedes ver los datos recibidos desde el formulario y buscar pedidos registrados.</p>
      </div>

      <!-- ---- Resultado del registro por POST ---- -->
      <?php if (!empty($error)): ?>

        <div class="alerta error">⚠️ <?php echo $error; ?></div>
        <a href="formulario_pedido.php" class="btn-volver">← Volver al formulario</a>

      <?php elseif ($pedido_guardado !== null): ?>

        <div class="alerta exito">
          ✅ ¡Pedido registrado correctamente! Los datos fueron recibidos via <strong>$_POST</strong>.
        </div>

        <!-- Tabla con los datos recuperados del formulario -->
        <div class="form-card">
          <h3>📋 Datos recibidos del formulario (método POST)</h3>

          <table class="tabla-resultado">
            <thead>
              <tr>
                <th>Campo del formulario</th>
                <th>Variable PHP</th>
                <th>Valor recibido</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Descripción</td>
                <td><code>$_POST['descripcion']</code></td>
                <td><?php echo $pedido_guardado->getDescripcion(); ?></td>
              </tr>
              <tr>
                <td>Tipo de pedido</td>
                <td><code>$_POST['tipoPedido']</code></td>
                <td><?php echo $pedido_guardado->getTipoPedido(); ?></td>
              </tr>
              <tr>
                <td>Producto</td>
                <td><code>$_POST['producto']</code></td>
                <td><?php echo $pedido_guardado->getProducto(); ?></td>
              </tr>
              <tr>
                <td>Unidades</td>
                <td><code>$_POST['unidades']</code></td>
                <td><?php echo $pedido_guardado->getUnidades(); ?></td>
              </tr>
              <tr>
                <td>Observaciones</td>
                <td><code>$_POST['observaciones']</code></td>
                <td>
                  <?php
                    $obs = $pedido_guardado->getObservaciones();
                    echo !empty($obs) ? $obs : "<em>Sin observaciones</em>";
                  ?>
                </td>
              </tr>
              <tr>
                <td>Fecha de registro</td>
                <td><em>(generado automáticamente)</em></td>
                <td><?php echo $pedido_guardado->getFecha(); ?></td>
              </tr>
              <tr>
                <td>Estado inicial</td>
                <td><em>(generado automáticamente)</em></td>
                <td><?php echo $pedido_guardado->getEstado(); ?></td>
              </tr>
            </tbody>
          </table>

          <!-- Resumen usando el método getResumen() de la clase -->
          <div class="resumen-pedido">
            <strong>Resumen:</strong>
            <?php echo $pedido_guardado->getResumen(); ?>
          </div>
        </div>

      <?php endif; ?>

      <!-- ---- Sección de búsqueda via GET ---- -->
      <div class="form-card">
        <h3>🔍 Buscar pedidos (método GET)</h3>
        <p style="font-size:0.82rem; color:#777; margin-bottom:14px;">
          El término de búsqueda se pasa por la URL usando <code>$_GET['buscar']</code>.
          Ejemplo: <code>procesar_pedido.php?buscar=laptop</code>
        </p>

        <!-- Formulario de búsqueda usando GET -->
        <form method="GET" action="procesar_pedido.php" class="form-busqueda">
          <div class="campo">
            <label for="buscar">Buscar en mis pedidos:</label>
            <div class="busqueda-fila">
              <input
                type="text"
                id="buscar"
                name="buscar"
                placeholder="Ej: laptop, iPhone, mouse..."
                value="<?php echo htmlspecialchars($termino_busqueda); ?>"
              >
              <button type="submit" class="btn-buscar">Buscar</button>
              <?php if (!empty($termino_busqueda)): ?>
                <a href="procesar_pedido.php" class="btn-limpiar">✕ Limpiar</a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>

      <!-- Tabla con todos los pedidos o los resultados filtrados -->
      <div class="resenas-lista">

        <?php if (!empty($termino_busqueda)): ?>
          <h3>
            Resultados para "<strong><?php echo htmlspecialchars($termino_busqueda); ?></strong>"
            (<?php echo count($pedidos_filtrados); ?> encontrado(s))
          </h3>
        <?php else: ?>
          <h3>Todos los pedidos registrados (<?php echo count($_SESSION['pedidos']); ?>)</h3>
        <?php endif; ?>

        <?php if (empty($pedidos_filtrados)): ?>
          <p class="sin-resenas">
            <?php echo !empty($termino_busqueda)
              ? "No se encontraron pedidos con ese término."
              : "Aún no hay pedidos registrados."; ?>
          </p>

        <?php else: ?>
          <table class="tabla-resultado">
            <thead>
              <tr>
                <th>Descripción</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Unidades</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_reverse($pedidos_filtrados) as $p): ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['descripcion']); ?></td>
                  <td><?php echo htmlspecialchars($p['producto']); ?></td>
                  <td><?php echo htmlspecialchars($p['tipoPedido']); ?></td>
                  <td><?php echo htmlspecialchars($p['unidades']); ?></td>
                  <td><?php echo htmlspecialchars($p['estado']); ?></td>
                  <td><?php echo htmlspecialchars($p['fecha']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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
