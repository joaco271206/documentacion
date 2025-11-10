<?php
/**
 * Vista de lista de ventas
 * Mejoras: Diseño moderno, responsivo, mejor UX, validación de variables
 */

// Inicializar variables para evitar warnings
$ventas = $ventas ?? [];
$evento_id = $evento_id ?? '';
$fecha = $fecha ?? '';
$nombre_evento = $nombre_evento ?? '';
$eventos = $eventos ?? [];
$resumen = $resumen ?? ['total_ventas' => 0, 'total_entradas' => 0, 'monto_total' => 0];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ventas - Tickets</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .search-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .form-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            margin-top: 10px;
        }

        .btn-secondary:hover {
            box-shadow: 0 5px 20px rgba(108, 117, 125, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons button {
            flex: 1;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
        }

        @media print {
            .search-form,
            .action-buttons {
                display: none !important;
            }

            body {
                background: white;
                padding: 0;
            }

            .ticket-container {
                box-shadow: none;
                padding: 20px;
            }
        }

        .ticket-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .ticket-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .ticket-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .ticket-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .summary-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 28px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        td {
            color: #555;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .price {
            color: #27ae60;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .ticket-info, .summary-cards {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎫 Sistema de Gestión de Ventas</h1>

        <div class="search-form">
            <div class="form-title">Buscar Ventas por Evento y Fecha</div>
            
            <form method="get" action="index.php">
                <div class="form-group">
                    <label for="id_evento">Evento:</label>
                    <select name="id_evento" id="id_evento" required>
                        <option value="">Seleccione un evento</option>
                        <?php foreach ($eventos as $evt): ?>
                            <option value="<?= htmlspecialchars($evt['id_evento']) ?>" 
                                    <?= ($evt['id_evento'] == $evento_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($evt['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha">Fecha de compra:</label>
                    <input type="date" 
                           name="fecha" 
                           id="fecha" 
                           value="<?= htmlspecialchars($fecha) ?>" 
                           max="<?= date('Y-m-d') ?>"
                           required>
                </div>

                <div class="action-buttons">
                    <button type="submit">🔍 Buscar Ventas</button>
                </div>
            </form>
        </div>

        <?php if (!empty($ventas) || (isset($_GET['id_evento']) && isset($_GET['fecha']))): ?>
            <div class="ticket-container">
                <div class="ticket-header">
                    <h2>Reporte de Ventas</h2>
                    
                    <div class="ticket-info">
                        <div class="info-item">
                            <div class="info-label">Evento</div>
                            <div class="info-value"><?= htmlspecialchars($nombre_evento) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Fecha</div>
                            <div class="info-value"><?= htmlspecialchars($fecha) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Generado</div>
                            <div class="info-value"><?= date('d/m/Y H:i') ?></div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($ventas)): ?>
                    <div class="summary-cards">
                        <div class="summary-card">
                            <div class="summary-label">Total Ventas</div>
                            <div class="summary-value"><?= number_format($resumen['total_ventas']) ?></div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label">Entradas Vendidas</div>
                            <div class="summary-value"><?= number_format($resumen['total_entradas']) ?></div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label">Monto Total</div>
                            <div class="summary-value">$<?= number_format($resumen['monto_total'], 2) ?></div>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Email del Cliente</th>
                                <th style="text-align: center;">Cantidad</th>
                                <th style="text-align: right;">Precio Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td><?= htmlspecialchars($venta['email']) ?></td>
                                    <td style="text-align: center;"><?= htmlspecialchars($venta['cant_entradas']) ?></td>
                                    <td style="text-align: right;" class="price">
                                        $<?= number_format($venta['precio_total'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        ℹ️ No se encontraron ventas para este evento en la fecha seleccionada.
                    </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <button type="button" onclick="window.location.href='index.php'" class="btn-secondary">
                        🔄 Nueva Búsqueda
                    </button>
                    <button type="button" onclick="window.print()" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                        🖨️ Imprimir Reporte
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>