<?php
// ================================================
// formulario_pedido.php - Formulario de Registro de Pedido
// Programación Web II - Actividad 3
// Alumno: Claudio Baeza Henríquez
// ================================================

// Se incluye la clase Pedido para poder utilizarla
require_once "clase_pedido.php";

// Se inicia la sesión para acceder a los pedidos guardados
session_start();

// Se inicializa el arreglo de pedidos si no existe aún
if (!isset($_SESSION['pedidos'])) {
    $_SESSION['pedidos'] = [];
}

// Sanear la sesión: si por algún motivo se guardó un objeto Pedido
// en vez de un arreglo, se convierte usando toArray()
foreach ($_SESSION['pedidos'] as $indice => $item) {
    if (is_object($item) && method_exists($item, 'toArray')) {
        $_SESSION['pedidos'][$indice] = $item->toArray();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Registrar Pedido - Tienda Online</title>
</head>
<body>

  <!-- Encabezado -->
  <header>
    <h1>🛒 Mi Tienda Online</h1>
    <nav class="header-nav">
      <a href="index.php" class="nav-link">🏠 Inicio</a>
      <a href="resenas.php" class="nav-link">⭐ Reseñas</a>
    </nav>
  </header>

  <main>
    <div class="pagina-contenido">

      <!-- Banner de la página -->
      <div class="pagina-banner">
        <h2>📦 Registrar Pedido</h2>
        <p>Completa el formulario para registrar tu pedido. Los campos marcados con <strong>*</strong> son obligatorios.</p>
      </div>

      <!-- -------------------------------------------------------
           ACTIVIDAD 3: Formulario HTML basado en las propiedades
           de la clase Pedido:
           - descripcion    → campo de texto
           - tipoPedido     → select con opciones
           - producto       → select con productos de la tienda
           - unidades       → campo numérico
           - observaciones  → área de texto
           El formulario envía los datos a procesar_pedido.php
           usando el método POST
           ------------------------------------------------------- -->

      <div class="form-card">
        <h3>Datos del pedido</h3>

        <form method="POST" action="procesar_pedido.php">

          <!-- Campo: Descripción del pedido -->
          <div class="campo">
            <label for="descripcion">Descripción del pedido: *</label>
            <input
              type="text"
              id="descripcion"
              name="descripcion"
              placeholder="Ej: Pedido para oficina, regalo de cumpleaños..."
              required
            >
          </div>

          <!-- Campo: Tipo de pedido -->
          <div class="campo">
            <label for="tipoPedido">Tipo de pedido: *</label>
            <select id="tipoPedido" name="tipoPedido" required>
              <option value="">-- Selecciona el tipo --</option>
              <option value="Normal">Normal (5 a 7 días hábiles)</option>
              <option value="Urgente">Urgente (24 a 48 horas)</option>
              <option value="Programado">Programado (fecha acordada)</option>
            </select>
          </div>

          <!-- Campo: Producto solicitado -->
          <div class="campo">
            <label for="producto">Producto: *</label>
            <select id="producto" name="producto" required>
              <option value="">-- Selecciona un producto --</option>
              <optgroup label="💻 Laptops">
                <option value="Laptop HP 15">Laptop HP 15 — $450.000</option>
                <option value="Laptop Lenovo IdeaPad">Laptop Lenovo IdeaPad — $380.000</option>
                <option value="MacBook Air M2">MacBook Air M2 — $1.200.000</option>
              </optgroup>
              <optgroup label="📱 Celulares">
                <option value="iPhone 14">iPhone 14 — $750.000</option>
                <option value="Samsung Galaxy A54">Samsung Galaxy A54 — $320.000</option>
                <option value="Xiaomi Redmi Note 12">Xiaomi Redmi Note 12 — $199.990</option>
              </optgroup>
              <optgroup label="🖱️ Accesorios">
                <option value="Mouse inalámbrico">Mouse inalámbrico — $18.990</option>
                <option value="Teclado USB">Teclado USB — $25.990</option>
                <option value="Audífonos Bluetooth">Audífonos Bluetooth — $45.990</option>
              </optgroup>
            </select>
          </div>

          <!-- Campo: Unidades -->
          <div class="campo">
            <label for="unidades">Cantidad de unidades: *</label>
            <input
              type="number"
              id="unidades"
              name="unidades"
              min="1"
              max="100"
              value="1"
              required
            >
          </div>

          <!-- Campo: Observaciones -->
          <div class="campo">
            <label for="observaciones">Observaciones:</label>
            <textarea
              id="observaciones"
              name="observaciones"
              rows="4"
              placeholder="Indicaciones especiales, dirección de entrega, horario preferido..."
            ></textarea>
          </div>

          <!-- Botón de envío -->
          <button type="submit" class="btn-enviar">
            📦 Registrar pedido
          </button>

        </form>
      </div>

      <!-- Listado de pedidos registrados en la sesión actual -->
      <?php if (!empty($_SESSION['pedidos'])): ?>
        <div class="resenas-lista">
          <h3>Pedidos registrados en esta sesión (<?php echo count($_SESSION['pedidos']); ?>)</h3>

          <table class="tabla-resultado">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Unidades</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_reverse($_SESSION['pedidos']) as $p): ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['producto']); ?></td>
                  <td><?php echo htmlspecialchars($p['tipoPedido']); ?></td>
                  <td><?php echo htmlspecialchars($p['unidades']); ?></td>
                  <td><?php echo htmlspecialchars($p['estado']); ?></td>
                  <td><?php echo htmlspecialchars($p['fecha']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>Desarrollado por <strong>Claudio Baeza Henríquez</strong> &mdash; Programación Web II</p>
  </footer>

</body>
</html>
