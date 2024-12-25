<?php
//include '../includes/header.php';

session_start();

// Verificar si el usuario tiene permisos para acceder a esta página
if (!isset($_SESSION['usuario_id'])) {
    echo "<div class='alert alert-danger'>Acceso denegado.</div>";
    exit();
}

// Obtener el rol del usuario desde la sesión
$rol_usuario = $_SESSION['rol'];

// Obtener el parámetro 'view' para determinar qué vista mostrar
$view = isset($_GET['view']) ? $_GET['view'] : 'personas';

// Función para sanitizar el parámetro 'view'
function sanitize_view($view) {
    $allowed_views = ['personas', 'agregar', 'usuarios'];
    return in_array($view, $allowed_views) ? $view : 'personas';
}

$view = sanitize_view($view);

// Títulos correspondientes a cada vista
$view_titles = [
    'personas' => 'Tabla de Personas',
    'agregar' => 'Agregar Persona',
    'usuarios' => 'Tabla de Usuarios'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Personas y Usuarios</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #2980B9;
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            margin: 0; 
            padding: 0;
        }

        .table {
            background-color: #495057;
            color: #f3f4f6 !important;
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

        .active-view {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .button-container {
            display: flex;
            gap: 10px; /* Espaciado entre botones */
            justify-content: flex-start; /* Alinea los botones a la izquierda */
            align-items: center; /* Centra verticalmente */
        }
        .btn-icon {
            display: inline-flex !important;
            justify-content: center !important;
            align-items: center !important;
            width: 2.5rem !important;
            height: 2.5rem !important;
            border-radius: 50%;
            font-size: 1.2rem !important;
            margin: 0 5px !important;
            transition: background-color 0.3s ease !important;
        }

        .btn-edit {
            color: #fff !important;
            background-color: #007bff !important;
        }

        .btn-edit:hover {
            background-color: #0056b3 !important;
        }

        .btn-delete {
            color: #fff !important;
            background-color: #dc3545 !important;
        }

        .btn-delete:hover {
            background-color: #a71d2a !important;
        }

    </style>
</head>
<body>
    <div class="container mt-5 ml-5">
        <!-- Título General y Botones -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Gestión de Personas y Usuarios</h2>
            <div>
                <?php if ($rol_usuario === 'directivo'): ?>
                    <a href="/bitacora/bitacFront.php" class="btn btn-primary mr-2">Reportes</a>
                <?php endif; ?>
                <a href="https://44.212.37.154/gestion.php" class="btn btn-danger">Salir</a>
            </div>
        </div>

        <!-- Botones para cambiar de vista -->
        <div class="button-group">
            <a href="fABM.php?view=agregar" class="btn btn-info">Agregar Persona</a>
            <a href="fABM.php?view=personas" class="btn btn-info">Tabla de Personas</a>
            <a href="fABM.php?view=usuarios" class="btn btn-info">Tabla de Usuarios</a>
        </div>

        <div class="ml-2">
            <!-- Contenedor para las vistas -->
            <div id="personas-view" class="table-container" <?php echo ($view === 'personas') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
                <?php include 'fABM_pers.php'; ?>
            </div>
            <div id="agregar-view" class="table-container" <?php echo ($view === 'agregar') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
                <?php include 'fABM_add.php'; ?>
            </div>
            <div id="usuarios-view" class="table-container" <?php echo ($view === 'usuarios') ? 'style="display:block;"' : 'style="display:none;"'; ?>>
                <?php include 'fABM_usu.php'; ?>
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
            // Inicializar DataTables en todas las tablas
            $('#personasTable, #usuariosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "pageLength": 10
            });
        });
    </script>
</body>
</html>
