// =====================================================
// script.js - Tienda de Comercio Electrónico
// Programación Web II
// Alumno: Claudio Baeza Henríquez
// =====================================================


// -------------------------------------------------------
// ACTIVIDAD 2: Objetos JavaScript que representan productos
// -------------------------------------------------------

function Producto(id, nombre, categoria, precio, enOferta, emoji) {
  this.id        = id;
  this.nombre    = nombre;
  this.categoria = categoria;
  this.precio    = precio;      // precio en pesos chilenos
  this.enOferta  = enOferta;
  this.emoji     = emoji;

  // Método: formatea el precio en pesos chilenos ($650.000)
  this.getPrecio = function() {
    return "$" + this.precio.toLocaleString("es-CL");
  };

  // Método: retorna si el producto está en oferta o no
  this.estadoOferta = function() {
    if (this.enOferta) {
      return "¡En oferta!";
    } else {
      return "Precio regular";
    }
  };

  // Método: genera la tarjeta HTML del producto
  this.mostrarTarjeta = function() {
    var tarjeta = document.createElement("div");
    tarjeta.className = "product-card";

    var ofertaHTML = "";
    if (this.enOferta) {
      ofertaHTML = '<span class="oferta-badge">OFERTA</span>';
    }

    tarjeta.innerHTML =
      ofertaHTML +
      '<span class="emoji">' + this.emoji + '</span>' +
      "<h3>" + this.nombre + "</h3>" +
      '<p class="categoria">' + this.categoria + "</p>" +
      '<p class="precio">' + this.getPrecio() + "</p>" +
      '<p class="estado">' + this.estadoOferta() + "</p>" +
      '<button onclick="agregarAlCarrito(' + this.id + ')">' +
        "🛒 Agregar al carrito" +
      "</button>";

    return tarjeta;
  };
}

// Catálogo de productos con precios en pesos chilenos
var productos = [
  new Producto(1, "Laptop HP 15",          "laptop",     450000, false, "💻"),
  new Producto(2, "Laptop Lenovo IdeaPad", "laptop",     380000, true,  "💻"),
  new Producto(3, "MacBook Air M2",        "laptop",    1200000, false, "🍎"),
  new Producto(4, "iPhone 14",             "celular",    750000, true,  "📱"),
  new Producto(5, "Samsung Galaxy A54",    "celular",    320000, false, "📱"),
  new Producto(6, "Xiaomi Redmi Note 12",  "celular",    199990, true,  "📲"),
  new Producto(7, "Mouse inalámbrico",     "accesorio",   18990, false, "🖱️"),
  new Producto(8, "Teclado USB",           "accesorio",   25990, true,  "⌨️"),
  new Producto(9, "Audífonos Bluetooth",   "accesorio",   45990, false, "🎧"),
];


// -------------------------------------------------------
// ACTIVIDAD 1: Búsqueda y filtrado dinámico con el DOM
// -------------------------------------------------------

function searchProducts() {
  var texto       = document.getElementById("product-search").value.toLowerCase();
  var categoria   = document.getElementById("filter-category").value;
  var precioMax   = document.getElementById("filter-precio").value;
  var soloOfertas = document.getElementById("filter-oferta").checked;

  var resultados = productos.filter(function(p) {
    var coincideTexto     = p.nombre.toLowerCase().includes(texto);
    var coincideCategoria = categoria === "" || p.categoria === categoria;
    var coincidePrecio    = true;
    var coincideOferta    = true;

    if (precioMax !== "" && precioMax > 0) {
      coincidePrecio = p.precio <= parseFloat(precioMax);
    }
    if (soloOfertas) {
      coincideOferta = p.enOferta === true;
    }

    return coincideTexto && coincideCategoria && coincidePrecio && coincideOferta;
  });

  mostrarResultados(resultados);
}

function mostrarResultados(lista) {
  var contenedor = document.getElementById("results-container");
  var totalTexto = document.getElementById("total-resultados");

  contenedor.innerHTML = "";

  if (lista.length === 0) {
    var mensaje = document.createElement("p");
    mensaje.className = "sin-resultados";
    mensaje.textContent = "No se encontraron productos.";
    contenedor.appendChild(mensaje);
    totalTexto.textContent = "0 resultados";
    return;
  }

  for (var i = 0; i < lista.length; i++) {
    contenedor.appendChild(lista[i].mostrarTarjeta());
  }

  totalTexto.textContent =
    "Mostrando " + lista.length + " de " + productos.length + " productos";
}


// -------------------------------------------------------
// ACTIVIDAD 3: Eventos para notificaciones y carrito
// -------------------------------------------------------

var carrito = [];

function agregarAlCarrito(id) {
  var producto = null;
  for (var i = 0; i < productos.length; i++) {
    if (productos[i].id === id) {
      producto = productos[i];
      break;
    }
  }
  if (producto === null) return;

  var yaEsta = false;
  for (var j = 0; j < carrito.length; j++) {
    if (carrito[j].id === id) {
      carrito[j].cantidad++;
      yaEsta = true;
      break;
    }
  }
  if (!yaEsta) {
    carrito.push({
      id: producto.id,
      nombre: producto.nombre,
      precio: producto.precio,
      cantidad: 1
    });
  }

  actualizarContador();
  mostrarNotificacion("✅ " + producto.nombre + " agregado al carrito", "exito");

  if (producto.enOferta) {
    setTimeout(function() {
      mostrarNotificacion("🏷️ ¡Aprovecha! " + producto.nombre + " está en oferta", "promo");
    }, 1200);
  }
}

function actualizarContador() {
  var total = 0;
  for (var i = 0; i < carrito.length; i++) {
    total += carrito[i].cantidad;
  }
  document.getElementById("contador-carrito").textContent = total;
}

function verCarrito() {
  var panel   = document.getElementById("panel-carrito");
  var lista   = document.getElementById("lista-carrito");
  var totalEl = document.getElementById("total-carrito");

  panel.style.display = "block";
  lista.innerHTML = "";

  if (carrito.length === 0) {
    lista.innerHTML = "<li>El carrito está vacío</li>";
    totalEl.textContent = "$0";
    return;
  }

  var total = 0;
  for (var i = 0; i < carrito.length; i++) {
    var item = carrito[i];
    var li = document.createElement("li");
    var subtotal = item.precio * item.cantidad;
    li.textContent = item.nombre + " x" + item.cantidad +
      " — $" + subtotal.toLocaleString("es-CL");
    lista.appendChild(li);
    total += subtotal;
  }

  totalEl.textContent = "$" + total.toLocaleString("es-CL");
}

function cerrarCarrito() {
  document.getElementById("panel-carrito").style.display = "none";
}

function pagar() {
  if (carrito.length === 0) {
    mostrarNotificacion("⚠️ El carrito está vacío", "error");
    return;
  }
  carrito = [];
  actualizarContador();
  cerrarCarrito();
  mostrarNotificacion("🎉 ¡Pago realizado con éxito! Gracias por tu compra.", "exito");
}

function mostrarNotificacion(mensaje, tipo) {
  var area  = document.getElementById("notificaciones");
  var notif = document.createElement("div");
  notif.className = "notif " + tipo;
  notif.textContent = mensaje;
  area.appendChild(notif);

  setTimeout(function() {
    if (notif.parentElement) {
      notif.remove();
    }
  }, 3000);
}

// Al cargar la página: mostrar productos y notificación de bienvenida
window.onload = function() {
  mostrarResultados(productos);

  setTimeout(function() {
    mostrarNotificacion("👋 ¡Bienvenido! Tenemos ofertas especiales para ti.", "promo");
  }, 800);
};
