<?php
/**
 * Configuración de conexión a la base de datos
 * Mejoras: Uso de constantes, manejo de errores mejorado, charset UTF-8
 */

// Definir constantes para la configuración
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ticket');

// Crear conexión con manejo de errores mejorado
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer charset UTF-8 para evitar problemas con caracteres especiales
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error al establecer charset: " . $conn->error);
    }
    
} catch (Exception $e) {
    // En producción, no mostrar detalles del error
    error_log($e->getMessage());
    die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
}
?>