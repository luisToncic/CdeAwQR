<?php
include '../includes/db.php';

// Obtener parámetros de filtro
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';
$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$turno = isset($_GET['turno']) ? $_GET['turno'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Obtener opciones para el filtro de rol y estado de acceso
$roles = ['personal', 'alumno', 'docente', 'directivo', 'administrativo', 'invitado'];
$estados_acceso = ['dentro', 'fuera'];

// Construir la consulta con filtros
$query = "
    SELECT u.usuario, u.rol, u.estado, p.nombre, p.apellido, p.dni, p.turno, ba.fechaIngreso, ba.fechaEgreso  
    FROM bitacoraAccesos ba 
    JOIN usuarios u ON ba.idUsuario = u.id 
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

if (!empty($usuario)) {
    $query .= " AND u.usuario LIKE ?";
    $params[] = '%' . $usuario . '%';
    $types .= 's';
}

if (!empty($turno)) {
    $query .= " AND p.turno = ?";
    $params[] = $turno;
    $types .= 's';
}

if (!empty($estado)) {
    $query .= " AND u.estado = ?";
    $params[] = $estado;
    $types .= 's';
}

if (!empty($fecha_inicio)) {
    $query .= " AND ba.fechaIngreso >= ?";
    $params[] = $fecha_inicio;
    $types .= 's';
}

if (!empty($fecha_fin)) {
    $query .= " AND ba.fechaEgreso <= ?";
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

<!-- Formulario de Filtro para Bitácora de Accesos -->
<form method="GET" class="mb-4">
    <input type="hidden" name="view" value="accesos"> <!-- Indica la vista actual -->
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
        <!-- Filtro por Usuario -->
        <div class="col-md-3">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario); ?>" placeholder="Buscar por usuario">
        </div>
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
    </div>
    <div class="form-row mt-3">
        <!-- Filtro por Turno -->
        <div class="col-md-3">
            <label for="turno">Turno:</label>
            <select name="turno" id="turno" class="form-control">
                <option value="">Todos</option>
                <option value="Mañana" <?php echo ($turno === 'Mañana') ? 'selected' : ''; ?>>Mañana</option>
                <option value="Tarde" <?php echo ($turno === 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
                <option value="Noche" <?php echo ($turno === 'Noche') ? 'selected' : ''; ?>>Noche</option>
            </select>
        </div>
        <!-- Filtro por Estado -->
        <div class="col-md-3">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($estados_acceso as $estado_acceso): ?>
                    <option value="<?php echo htmlspecialchars($estado_acceso); ?>" <?php echo ($estado === $estado_acceso) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars(ucfirst($estado_acceso)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Filtro por Fecha de Ingreso -->
        <div class="col-md-3">
            <label for="fecha_inicio">Fecha Ingreso:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
        </div>
        <!-- Filtro por Fecha de Egreso -->
        <div class="col-md-3">
            <label for="fecha_fin">Fecha Egreso:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin); ?>">
        </div>
    </div>
    <div class="form-row mt-3">
        <!-- Botones de Acción -->
        <div class="col-md-12 d-flex align-items-end">
            <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
            <a href="bitacFront.php?view=accesos" class="btn btn-secondary mr-2">Restablecer</a>
        </div>
    </div>
</form>

<table id="accesosTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Turno</th>
            <th>Fecha Ingreso</th>
            <th>Fecha Egreso</th>
            <th>Estado</th>
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
                        <td>" . htmlspecialchars($row['turno'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['fechaIngreso'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['fechaEgreso'] ?? '') . "</td>
                        <td>" . htmlspecialchars($row['estado'] ?? '') . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No se encontraron registros.</td></tr>";
        }
        ?>
    </tbody>
</table>
