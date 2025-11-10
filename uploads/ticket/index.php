<?php
/**
 * Punto de entrada principal del sistema de ventas
 * Mejoras: Mejor manejo de sesiones, seguridad, y estructura
 */

// Configuración de errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión si es necesario
session_start();

// Incluir archivos necesarios en el orden correcto
require_once("config/conexion.php");
require_once("models/evento.php");
require_once("models/venta.php");
require_once("controllers/ventas.php");

// Crear instancia del modelo de eventos para obtener la lista
$eventoModel = new Evento($conn);
$eventos = $eventoModel->listarEventos();

// Si no hay parámetros, mostrar formulario de búsqueda
if (!isset($_GET['id_evento']) || !isset($_GET['fecha'])) {
    // Variables vacías para el formulario
    $ventas = [];
    $evento_id = '';
    $fecha = '';
    $nombre_evento = '';
    $resumen = ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
    
    include("views/lista_ventas.php");
    exit();
}

// Crear instancia del controlador
$controller = new VentasController($conn);

// Procesar la solicitud de listado
$controller->listar();

// Cerrar conexión al finalizar
$conn->close();
?>