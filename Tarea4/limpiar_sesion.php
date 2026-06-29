<?php
// limpiar_sesion.php - Limpia la sesión para reiniciar pruebas
session_start();
session_destroy();
header("Location: formulario_pedido.php");
exit();
?>
