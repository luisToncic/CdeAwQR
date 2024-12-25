<?php
session_start();
include '../includes/db.php';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario tiene permisos para acceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'directivo') {
    echo "<div class='alert alert-danger'>Acceso denegado.</div>";
    exit();
}

$acciones = ['Agregar Persona', 'Eliminar Persona', 'Eliminar Usuario', 'Modificar Persona', 'Modificar Usuario'];

// Obtener el parámetro 'view' para determinar qué vista mostrar
$view = isset($_GET['view']) ? $_GET['view'] : 'auditoria';

// Función para sanitizar el parámetro 'view'
function sanitize_view($view) {
    $allowed_views = ['auditoria', 'accesos', 'tokens'];
    return in_array($view, $allowed_views) ? $view : 'auditoria';
}

$view = sanitize_view($view);

// Títulos correspondientes a cada vista
$view_titles = [
    'auditoria' => 'Auditoría',
    'accesos' => 'Accesos',
    'tokens' => 'Tokens'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
   
    <style>
        body {
            background: #2980B9;
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
        }

        .table {
            background-color: #495057;
            color: #f3f4f6;
            border-radius: 8px;
        }

        .table thead th {
            background-color: #212529;
            border-color: #495057;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.2);
        }

        .button-group {
            margin-bottom: 20px;
        }

        .button-group a {
            margin-right: 10px;
        }

        .header-buttons {
            margin-bottom: 20px;
        }

        .active-view {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <!-- Título General y Botones -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Bitácora</h2>
            <div>
                <a href="https://44.212.37.154/dataEntry/fABM.php" class="btn btn-warning mr-2">Atrás</a>
                <a href="https://44.212.37.154/gestion.php" class="btn btn-danger">Salir</a>
            </div>
        </div>

        <!-- Indicador de Vista Activa -->
        <div class="active-view">
            <?php echo isset($view_titles[$view]) ? $view_titles[$view] : 'Auditoría'; ?>
        </div>

        <!-- Botones para cambiar de vista -->
        <div class="button-group">
            <a href="bitacFront.php?view=auditoria" class="btn btn-info">Bitácora de Auditoría</a>
            <a href="bitacFront.php?view=accesos" class="btn btn-info">Bitácora de Accesos</a>
            <a href="bitacFront.php?view=tokens" class="btn btn-info">Bitácora de Tokens</a>
        </div>

        <!-- Contenedor para las vistas -->
        <div id="auditoria-view" class="table-container" <?php echo ($view === 'auditoria') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
            <?php include 'bitacora_auditoria.php'; ?>
        </div>
        <div id="accesos-view" class="table-container" <?php echo ($view === 'accesos') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
            <?php include 'bitacora_accesos.php'; ?>
        </div>
        <div id="tokens-view" class="table-container" <?php echo ($view === 'tokens') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
            <?php include 'bitacora_tokens.php'; ?>
        </div>

        <!-- Botones de Exportar -->
        <div class="form-row mt-3">
            <div class="col-md-12 d-flex align-items-end">
                <?php
                // Construir la URL para exportar CSV incluyendo todos los parámetros GET actuales
                $export_params = $_GET;
                $export_params['format'] = 'csv';
                $export_url = 'generate_report.php?' . http_build_query($export_params);
                ?>
                <a href="<?php echo htmlspecialchars($export_url); ?>" class="btn btn-success mr-2" target="_blank">Exportar CSV</a>
            </div>
        </div>
    </div>


    <!-- jQuery, Bootstrap JS, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables solo en la tabla visible
            <?php if ($view === 'auditoria'): ?>
                $('#auditoriaTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    }
                });
            <?php elseif ($view === 'accesos'): ?>
                $('#accesosTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    }
                });
            <?php elseif ($view === 'tokens'): ?>
                $('#tokensTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    }
                });
            <?php endif; ?>

            // No es necesario manejar clics en los botones para cambiar de vista,
            // ya que se usan enlaces que recargan la página con el parámetro 'view'
        });
    </script>
</body>
</html>
