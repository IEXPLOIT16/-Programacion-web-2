<?php
// ================================================
// index.php - Tienda de Comercio Electrónico
// Programación Web II
// Alumno: Claudio Baeza Henríquez
// ================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Tienda de Comercio Electrónico</title>
</head>
<body>

  <!-- Notificaciones -->
  <div id="notificaciones"></div>

  <!-- Encabezado -->
  <header>
    <h1>🛒 Mi Tienda Online</h1>
    <nav class="header-nav">
      <a href="formulario_pedido.php" class="nav-link">📦 Hacer pedido</a>
      <a href="resenas.php" class="nav-link">⭐ Reseñas</a>
      <button id="btn-carrito" onclick="verCarrito()">
        Ver carrito (<span id="contador-carrito">0</span>)
      </button>
    </nav>
  </header>

  <!-- Contenido principal -->
  <main>

    <!-- Banner de bienvenida -->
    <div class="hero">
      <div class="hero-texto">
        <h2>Bienvenido a <span>Mi Tienda Online</span></h2>
        <p>Encuentra los mejores productos de tecnología al mejor precio en Chile.</p>
      </div>
      <div class="hero-stats">
        <div class="stat">
          <strong>9</strong>
          <span>Productos</span>
        </div>
        <div class="stat">
          <strong>3</strong>
          <span>Categorías</span>
        </div>
        <div class="stat">
          <strong>4</strong>
          <span>En oferta</span>
        </div>
      </div>
    </div>

    <!-- Buscador (código base mejorado) -->
    <div class="seccion-busqueda">
      <div class="search-container">
        <input type="text" id="product-search" placeholder="Buscar producto">
        <button onclick="searchProducts()">Buscar</button>
      </div>

      <!-- Filtros agregados -->
      <div class="filtros">
        <label>Categoría:
          <select id="filter-category" onchange="searchProducts()">
            <option value="">Todas</option>
            <option value="laptop">Laptops</option>
            <option value="celular">Celulares</option>
            <option value="accesorio">Accesorios</option>
          </select>
        </label>

        <label>Precio máximo:
          <input type="number" id="filter-precio" placeholder="Ej: 500000" onchange="searchProducts()">
        </label>

        <label>
          <input type="checkbox" id="filter-oferta" onchange="searchProducts()">
          Solo ofertas
        </label>
      </div>
    </div><!-- fin seccion-busqueda -->

    <!-- Resultados -->
    <p id="total-resultados"></p>
    <div id="results-container"></div>

  </main>

  <!-- Carrito -->
  <div id="panel-carrito" style="display:none">
    <h2>🛒 Carrito de compras</h2>
    <ul id="lista-carrito"></ul>
    <p class="carrito-total">Total: <span id="total-carrito">$0</span></p>
    <button class="btn-cerrar" onclick="cerrarCarrito()">Cerrar</button>
    <button class="btn-pagar" onclick="pagar()">Pagar</button>
  </div>

  <!-- Footer -->
  <footer>
    <p>Desarrollado por <strong>Claudio Baeza Henríquez</strong> &mdash; Programación Web II</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
