<?php
/**
 * Modelo Evento
 * Mejoras: Manejo de errores, cierre de statements, validación de entrada
 */

class Evento {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Verifica si existe un evento por su ID
     * @param int $evento_id ID del evento
     * @return bool True si existe, False si no
     */
    public function existeEvento($evento_id) {
        // Validar que el ID sea un número entero positivo
        if (!is_numeric($evento_id) || $evento_id <= 0) {
            return false;
        }

        $sql = "SELECT COUNT(*) as total FROM Eventos WHERE id_evento = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Error al preparar consulta: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $evento_id);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['total'] > 0;
    }

    /**
     * Obtiene información de un evento por su ID
     * @param int $evento_id ID del evento
     * @return array|null Datos del evento o null si no existe
     */
    public function obtenerEvento($evento_id) {
        if (!is_numeric($evento_id) || $evento_id <= 0) {
            return null;
        }

        // Solo seleccionar columnas que existen en la tabla
        $sql = "SELECT id_evento, nombre, precio FROM Eventos WHERE id_evento = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Error al preparar consulta: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $evento_id);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $evento = $result->fetch_assoc();
        $stmt->close();
        
        return $evento;
    }

    /**
     * Obtiene todos los eventos activos
     * @return array Lista de eventos
     */
    public function listarEventos() {
        $sql = "SELECT id_evento, nombre FROM Eventos ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            error_log("Error al listar eventos: " . $this->conn->error);
            return [];
        }

        $eventos = [];
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        
        return $eventos;
    }
}
?>