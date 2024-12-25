<?php
include '../includes/db.php';

// Obtener parámetros
$view = isset($_GET['view']) ? $_GET['view'] : 'auditoria';
$format = isset($_GET['format']) ? $_GET['format'] : 'csv'; // Cambia a 'csv' por defecto

// Sanitizar parámetros
$allowed_views = ['auditoria', 'accesos', 'tokens'];
$allowed_formats = ['csv'];

if (!in_array($view, $allowed_views)) {
    die("Vista no válida para generar el reporte.");
}

if (!in_array($format, $allowed_formats)) {
    die("Formato de reporte no válido.");
}

// Construir la consulta según la vista
switch ($view) {
    case 'auditoria':
        $headers = ['Nombre', 'Apellido', 'DNI', 'Usuario', 'Rol', 'Acción', 'Descripción', 'Fecha'];
        $query = "
            SELECT p.nombre, p.apellido, p.dni, u.usuario, u.rol, a.accion, a.descripcion, a.fecha 
            FROM auditoria a 
            JOIN usuarios u ON a.idUsuario = u.id 
            JOIN personas p ON u.legajo = p.legajo 
            WHERE 1=1
        ";

        // Aplicar filtros si están definidos
        if (!empty($_GET['apellido'])) {
            $query .= " AND p.apellido LIKE '%" . $conn->real_escape_string($_GET['apellido']) . "%'";
        }

        if (!empty($_GET['accion'])) {
            $query .= " AND a.accion = '" . $conn->real_escape_string($_GET['accion']) . "'";
        }

        if (!empty($_GET['usuario'])) {
            $query .= " AND u.id = " . intval($_GET['usuario']);
        }

        break;

    case 'accesos':
        $headers = ['Nombre', 'Apellido', 'DNI', 'Usuario', 'Rol', 'Fecha Ingreso', 'Fecha Egreso', 'Estado'];
        $query = "
            SELECT p.nombre, p.apellido, p.dni, u.usuario, u.rol, ba.fechaIngreso, ba.fechaEgreso, ba.estado 
            FROM bitacoraAccesos ba 
            JOIN usuarios u ON ba.idUsuario = u.id 
            JOIN personas p ON u.legajo = p.legajo 
            WHERE 1=1
        ";

        // Aplicar filtros si están definidos
        if (!empty($_GET['apellido'])) {
            $query .= " AND p.apellido LIKE '%" . $conn->real_escape_string($_GET['apellido']) . "%'";
        }

        if (!empty($_GET['usuario'])) {
            $query .= " AND u.id = " . intval($_GET['usuario']);
        }

        if (!empty($_GET['estado'])) {
            $query .= " AND ba.estado = '" . $conn->real_escape_string($_GET['estado']) . "'";
        }

        if (!empty($_GET['fecha_inicio'])) {
            $query .= " AND ba.fechaIngreso >= '" . $conn->real_escape_string($_GET['fecha_inicio']) . "'";
        }

        if (!empty($_GET['fecha_fin'])) {
            $query .= " AND ba.fechaEgreso <= '" . $conn->real_escape_string($_GET['fecha_fin']) . "'";
        }

        break;

    case 'tokens':
        $headers = ['Nombre', 'Apellido', 'DNI', 'Usuario', 'Rol', 'Token', 'Fecha Creación', 'Fecha Expiración', 'Estado Acceso'];
        $query = "
            SELECT p.nombre, p.apellido, p.dni, u.usuario, u.rol, t.token, t.fecha_creacion, t.fecha_expiracion, ba.estado 
            FROM tokens t 
            JOIN usuarios u ON t.usuario_id = u.id 
            JOIN personas p ON u.legajo = p.legajo
            LEFT JOIN bitacoraAccesos ba ON t.usuario_id = ba.idUsuario
            WHERE 1=1
        ";

        // Aplicar filtros si están definidos
        if (!empty($_GET['apellido'])) {
            $query .= " AND p.apellido LIKE '%" . $conn->real_escape_string($_GET['apellido']) . "%'";
        }

        if (!empty($_GET['token'])) {
            $query .= " AND t.token LIKE '%" . $conn->real_escape_string($_GET['token']) . "%'";
        }

        if (!empty($_GET['usuario'])) {
            $query .= " AND u.id = " . intval($_GET['usuario']);
        }

        if (!empty($_GET['estado'])) {
            $query .= " AND ba.estado = '" . $conn->real_escape_string($_GET['estado']) . "'";
        }

        if (!empty($_GET['fecha_inicio'])) {
            $query .= " AND t.fecha_creacion >= '" . $conn->real_escape_string($_GET['fecha_inicio']) . "'";
        }

        if (!empty($_GET['fecha_fin'])) {
            $query .= " AND t.fecha_expiracion <= '" . $conn->real_escape_string($_GET['fecha_fin']) . "'";
        }

        break;

    default:
        die("Vista no válida para generar el reporte.");
}

// Ejecutar la consulta
$result = $conn->query($query);

// Verificar si la consulta tuvo éxito
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Generar el archivo CSV
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $view . '_reporte.csv"');

    $output = fopen('php://output', 'w');

    // Escribir los encabezados
    fputcsv($output, $headers);

    // Escribir los datos
    while ($row = $result->fetch_assoc()) {
        $csv_row = [];
        foreach ($headers as $header) {
            // Asignar los valores según el encabezado
            switch ($header) {
                case 'Nombre':
                    $csv_row[] = $row['nombre'];
                    break;
                case 'Apellido':
                    $csv_row[] = $row['apellido'];
                    break;
                case 'DNI':
                    $csv_row[] = $row['dni'];
                    break;
                case 'Usuario':
                    $csv_row[] = $row['usuario'];
                    break;
                case 'Rol':
                    $csv_row[] = $row['rol'];
                    break;
                case 'Acción':
                    $csv_row[] = $row['accion'];
                    break;
                case 'Descripción':
                    $csv_row[] = $row['descripcion'];
                    break;
                case 'Fecha':
                    $csv_row[] = $row['fecha'];
                    break;
                case 'Fecha Ingreso':
                    $csv_row[] = $row['fechaIngreso'];
                    break;
                case 'Fecha Egreso':
                    $csv_row[] = $row['fechaEgreso'];
                    break;
                case 'Estado':
                    $csv_row[] = $row['estado'];
                    break;
                case 'Token':
                    $csv_row[] = $row['token'];
                    break;
                case 'Fecha Creación':
                    $csv_row[] = $row['fecha_creacion'];
                    break;
                case 'Fecha Expiración':
                    $csv_row[] = $row['fecha_expiracion'];
                    break;
                case 'Estado Acceso':
                    $csv_row[] = $row['estado'];
                    break;
                default:
                    $csv_row[] = '';
            }
        }
        fputcsv($output, $csv_row);
    }

    fclose($output);
    exit();
}
?>
