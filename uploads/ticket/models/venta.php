<?php
/**
 * Modelo Venta
 * Mejoras: Validación de entrada, manejo de errores, cierre de statements
 */

class Venta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lista las ventas de un evento en una fecha específica
     * @param int $evento_id ID del evento
     * @param string $fecha Fecha de compra (formato: Y-m-d)
     * @return array Lista de ventas
     */
    public function listarVentas($evento_id, $fecha) {
        // Validar entrada
        if (!is_numeric($evento_id) || $evento_id <= 0) {
            return [];
        }

        // Validar formato de fecha
        if (!$this->validarFecha($fecha)) {
            return [];
        }

        $sql = "SELECT 
                    u.email,
                    v.cant_entradas,
                    e.precio,
                    (v.cant_entradas * e.precio) AS precio_total,
                    v.fecha_compra
                FROM Ventas v
                JOIN Eventos e ON v.id_evento = e.id_evento
                JOIN Usuarios u ON v.id_usuario = u.id_usuario
                WHERE v.id_evento = ? AND DATE(v.fecha_compra) = ?
                ORDER BY v.fecha_compra DESC";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Error al preparar consulta: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("is", $evento_id, $fecha);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $ventas = [];
        
        while ($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }
        
        $stmt->close();
        
        return $ventas;
    }

    /**
     * Obtiene el total de ventas y cantidad de entradas vendidas
     * @param int $evento_id ID del evento
     * @param string $fecha Fecha de compra
     * @return array Resumen de ventas
     */
    public function obtenerResumen($evento_id, $fecha) {
        if (!is_numeric($evento_id) || $evento_id <= 0 || !$this->validarFecha($fecha)) {
            return ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
        }

        $sql = "SELECT 
                    COUNT(*) as total_ventas,
                    SUM(v.cant_entradas) as total_entradas,
                    SUM(v.cant_entradas * e.precio) as monto_total
                FROM Ventas v
                JOIN Eventos e ON v.id_evento = e.id_evento
                WHERE v.id_evento = ? AND DATE(v.fecha_compra) = ?";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Error al preparar consulta: " . $this->conn->error);
            return ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
        }

        $stmt->bind_param("is", $evento_id, $fecha);
        
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . $stmt->error);
            $stmt->close();
            return ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
        }

        $result = $stmt->get_result();
        $resumen = $result->fetch_assoc();
        $stmt->close();
        
        return [
            'total_ventas' => $resumen['total_ventas'] ?? 0,
            'total_entradas' => $resumen['total_entradas'] ?? 0,
            'monto_total' => $resumen['monto_total'] ?? 0
        ];
    }

    /**
     * Valida el formato de una fecha
     * @param string $fecha Fecha a validar
     * @return bool True si es válida, False si no
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