<?php
include '../includes/db.php';

// Obtener parámetros de filtro
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$rol = isset($_GET['rol']) ? $_GET['rol'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Obtener opciones para el filtro de rol
$roles = ['personal', 'alumno', 'docente', 'directivo', 'administrativo', 'invitado'];

// Construir la consulta con filtros
$query = "
    SELECT u.usuario, u.rol, p.nombre, p.apellido, p.dni, a.accion, a.descripcion, a.fecha 
    FROM auditoria a 
    JOIN usuarios u ON a.idUsuario = u.id 
    JOIN personas p ON u.legajo = p.legajo 
    WHERE 1=1
";

// Aplicar filtros si están establecidos
$params = [];
$types = '';

if (!empty($apellido)) {
    $query .= " AND p.apellido LIKE ?";
    $params[] = '%' . $apellido . '%';
    $types .= 's';
}

if (!empty($dni)) {
    $query .= " AND p.dni = ?";
    $params[] = $dni;
    $types .= 's';
}

if (!empty($accion)) {
    $query .= " AND a.accion = ?";
    $params[] = $accion;
    $types .= 's';
}

if (!empty($usuario)) {
    $query .= " AND u.usuario LIKE ?";
    $params[] = '%' . $usuario . '%';
    $types .= 's';
}

if (!empty($rol)) {
    $query .= " AND u.rol = ?";
    $params[] = $rol;
    $types .= 's';
}

if (!empty($fecha_inicio)) {
    $query .= " AND a.fecha >= ?";
    $params[] = $fecha_inicio;
    $types .= 's';
}

if (!empty($fecha_fin)) {
    $query .= " AND a.fecha <= ?";
    $params[] = $fecha_fin;
    $types .= 's';
}

// Preparar y ejecutar consulta
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Formulario de Filtro para Bitácora de Auditoría -->
<form method="GET" class="mb-4">
    <div class="form-row">
        <!-- Filtro por Apellido -->
        <div class="col-md-3">
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo htmlspecialchars($apellido); ?>" placeholder="Buscar por apellido">
        </div>
        <!-- Filtro por DNI -->
        <div class="col-md-3">
            <label for="dni">DNI:</label>
            <input type="text" name="dni" id="dni" class="form-control" value="<?php echo htmlspecialchars($dni); ?>" placeholder="Buscar por DNI">
        </div>
        <!-- Filtro por Acción -->
        <div class="col-md-3">
            <label for="accion">Acción:</label>
            <select name="accion" id="accion" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($acciones as $acc): ?>
                    <option value="<?php echo htmlspecialchars($acc); ?>" <?php echo ($accion === $acc) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($acc); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Filtro por Usuario -->
        <div class="col-md-3">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario); ?>" placeholder="Buscar por usuario">
        </div>
    </div>
    <div class="form-row mt-3">
        <!-- Filtro por Rol -->
        <div class="col-md-3">
            <label for="rol">Rol:</label>
            <select name="rol" id="rol" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?php echo htmlspecialchars($r); ?>" <?php echo ($rol === $r) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($r); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Filtro por Fecha de Inicio -->
        <div class="col-md-3">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
        </div>
        <!-- Filtro por Fecha de Fin -->
        <div class="col-md-3">
            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin); ?>">
        </div>
        <!-- Botones de Acción -->
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
            <a href="bitacFront.php?view=auditoria" class="btn btn-secondary mr-2">Restablecer</a>
        </div>
    </div>
</form>

<table id="auditoriaTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acción</th>
            <th>Descripción</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['nombre'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['apellido'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['dni'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['usuario'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['rol'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['accion'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['descripcion'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['fecha'] ?? '') . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No se encontraron registros.</td></tr>";
        }
        ?>
    </tbody>
</table>
