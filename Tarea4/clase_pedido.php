<?php
// ================================================
// clase_pedido.php - Clase Pedido en PHP
// Programación Web II - Actividad 2
// Alumno: Claudio Baeza Henríquez
// ================================================

/**
 * Clase Pedido
 *
 * Representa un pedido realizado por un cliente en la tienda.
 * Contiene las propiedades que describen el pedido y métodos
 * que permiten su gestión y búsqueda personalizada.
 */
class Pedido {

    // -------------------------------------------------------
    // PROPIEDADES de la clase
    // Cada propiedad describe un aspecto del pedido
    // -------------------------------------------------------

    private $descripcion;   // Descripción general del pedido
    private $tipoPedido;    // Tipo: "normal", "urgente" o "programado"
    private $producto;      // Nombre del producto solicitado
    private $unidades;      // Cantidad de unidades pedidas
    private $observaciones; // Notas adicionales del cliente
    private $fecha;         // Fecha en que se registró el pedido
    private $estado;        // Estado actual: "pendiente", "en proceso", "enviado"

    // -------------------------------------------------------
    // CONSTRUCTOR
    // Se ejecuta automáticamente al crear un objeto Pedido
    // con el operador new
    // -------------------------------------------------------

    public function __construct($descripcion, $tipoPedido, $producto, $unidades, $observaciones) {
        $this->descripcion   = $descripcion;
        $this->tipoPedido    = $tipoPedido;
        $this->producto      = $producto;
        $this->unidades      = $unidades;
        $this->observaciones = $observaciones;
        $this->fecha         = date("d/m/Y H:i"); // se registra la fecha automáticamente
        $this->estado        = "Pendiente";        // estado inicial siempre es pendiente
    }

    // -------------------------------------------------------
    // MÉTODOS de la clase
    // -------------------------------------------------------

    // Método: retorna la descripción del pedido
    public function getDescripcion() {
        return $this->descripcion;
    }

    // Método: retorna el tipo de pedido
    public function getTipoPedido() {
        return $this->tipoPedido;
    }

    // Método: retorna el nombre del producto
    public function getProducto() {
        return $this->producto;
    }

    // Método: retorna la cantidad de unidades
    public function getUnidades() {
        return $this->unidades;
    }

    // Método: retorna las observaciones del cliente
    public function getObservaciones() {
        return $this->observaciones;
    }

    // Método: retorna la fecha de registro
    public function getFecha() {
        return $this->fecha;
    }

    // Método: retorna el estado actual del pedido
    public function getEstado() {
        return $this->estado;
    }

    // Método: permite actualizar el estado del pedido
    public function setEstado($nuevoEstado) {
        $estados_validos = ["Pendiente", "En proceso", "Enviado", "Entregado"];
        if (in_array($nuevoEstado, $estados_validos)) {
            $this->estado = $nuevoEstado;
        }
    }

    // Método: retorna un resumen del pedido en texto
    public function getResumen() {
        return "Pedido de " . $this->unidades . " unidad(es) de " .
               $this->producto . " (" . $this->tipoPedido . ") — Estado: " . $this->estado;
    }

    // Método: retorna todos los datos del pedido como arreglo
    // Útil para guardar en sesión o mostrar en tabla
    public function toArray() {
        return [
            "descripcion"   => $this->descripcion,
            "tipoPedido"    => $this->tipoPedido,
            "producto"      => $this->producto,
            "unidades"      => $this->unidades,
            "observaciones" => $this->observaciones,
            "fecha"         => $this->fecha,
            "estado"        => $this->estado
        ];
    }

    // Método: busca si el pedido corresponde a un producto específico
    // Retorna true si el nombre del producto coincide (búsqueda flexible)
    public function buscarPorProducto($terminoBusqueda) {
        return stripos($this->producto, $terminoBusqueda) !== false;
    }

    // Método: busca si el pedido corresponde a un tipo específico
    public function buscarPorTipo($tipo) {
        return strtolower($this->tipoPedido) === strtolower($tipo);
    }

    // Método estático: filtra una lista de pedidos por producto
    // Recibe un arreglo de datos de pedidos y un término de búsqueda
    public static function filtrarPorProducto($listaPedidos, $termino) {
        $resultados = [];
        foreach ($listaPedidos as $datosPedido) {
            // Se reconstruye cada pedido para usar sus métodos
            $pedido = new Pedido(
                $datosPedido['descripcion'],
                $datosPedido['tipoPedido'],
                $datosPedido['producto'],
                $datosPedido['unidades'],
                $datosPedido['observaciones']
            );
            // Si coincide con la búsqueda, se agrega al resultado
            if ($pedido->buscarPorProducto($termino)) {
                $resultados[] = $datosPedido;
            }
        }
        return $resultados;
    }
}
?>
