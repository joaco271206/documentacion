<?php
/**
 * Controlador de Ventas
 * Maneja la lógica de negocio para el listado de ventas
 */

class VentasController {
    private $conn;
    private $eventoModel;
    private $ventaModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->eventoModel = new Evento($db);
        $this->ventaModel = new Venta($db);
    }

    /**
     * Lista las ventas de un evento en una fecha específica
     */
    public function listar() {
        // Obtener y validar parámetros
        $evento_id = $_GET['id_evento'] ?? null;
        $fecha = $_GET['fecha'] ?? null;

        // Validar que los parámetros existan
        if (empty($evento_id) || empty($fecha)) {
            $this->mostrarError("Debe seleccionar un evento y una fecha.");
            return;
        }

        // Validar que el evento_id sea numérico
        if (!is_numeric($evento_id) || $evento_id <= 0) {
            $this->mostrarError("ID de evento inválido.");
            return;
        }

        // Validar formato de fecha
        if (!$this->validarFecha($fecha)) {
            $this->mostrarError("Formato de fecha inválido. Use el formato AAAA-MM-DD.");
            return;
        }

        // Verificar que el evento existe
        if (!$this->eventoModel->existeEvento($evento_id)) {
            $this->mostrarError("El evento seleccionado no existe.");
            return;
        }

        // Obtener información del evento
        $evento = $this->eventoModel->obtenerEvento($evento_id);
        $nombre_evento = $evento['nombre'] ?? 'Evento desconocido';

        // Obtener ventas
        $ventas = $this->ventaModel->listarVentas($evento_id, $fecha);

        // Obtener resumen
        $resumen = $this->ventaModel->obtenerResumen($evento_id, $fecha);

        // Obtener lista de eventos para el formulario
        $eventos = $this->eventoModel->listarEventos();

        // Incluir la vista con los datos
        include("views/lista_ventas.php");
    }

    /**
     * Muestra un mensaje de error
     */
    private function mostrarError($mensaje) {
        $error = $mensaje;
        $eventos = $this->eventoModel->listarEventos();
        $evento_id = $_GET['id_evento'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        $ventas = [];
        $nombre_evento = '';
        $resumen = ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
        
        include("views/error.php");
    }

    /**
     * Valida el formato de una fecha
     */
    private function validarFecha($fecha) {
        if (empty($fecha)) {
            return false;
        }

        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        return $d && $d->format('Y-m-d') === $fecha;
    }
}
?>
