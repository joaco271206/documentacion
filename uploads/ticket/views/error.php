<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Sistema de Tickets</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        h1 {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 28px;
        }

        p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .btn-volver {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-volver:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .error-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1>Error en la solicitud</h1>
        <p>
            <?php 
            echo isset($mensaje) ? htmlspecialchars($mensaje) : 
                'Verifique los parámetros o que el evento exista.'; 
            ?>
        </p>
        
        <a href="index.php" class="btn-volver">← Volver al inicio</a>
        
        <div class="error-details">
            <strong>Posibles causas:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                <li>El ID del evento no es válido</li>
                <li>La fecha no tiene el formato correcto</li>
                <li>El evento no existe en la base de datos</li>
                <li>Faltan parámetros requeridos</li>
            </ul>
        </div>
    </div>
</body>
</html>