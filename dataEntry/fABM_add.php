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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Persona</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background: #2980B9;
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Título -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Agregar Persona</h2>
            <!-- <a href="fABM.php?view=personas" class="btn btn-primary">Volver a la Tabla de Personas</a> -->
        </div>

        <!-- Formulario para agregar persona -->
        <form action="bABM.php" method="POST">
            <input type="hidden" name="form_type" value="personas">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" oninput='validarLetras(this)' required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" name="apellido" oninput='validarLetras(this)' required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="edad" class="form-label">Edad:</label>
                    <input type="number" class="form-control" name="edad" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="dni" class="form-label">DNI:</label>
                    <input type="text" class="form-control" name="dni" maxlength="8" oninput="validarNumerico(this)" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mail" class="form-label">Mail:</label>
                    <input type="email" class="form-control" name="mail" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="text" class="form-control" name="telefono" oninput="validarNumerico(this)" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="direccion" class="form-label">Dirección:</label>
                    <input type="text" class="form-control" name="direccion" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="localidad" class="form-label">Localidad:</label>
                    <input type="text" class="form-control" name="localidad" oninput='validarLetras(this)' required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="legajo" class="form-label">Legajo:</label>
                    <input type="text" class="form-control" name="legajo" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="carrera" class="form-label">Carrera:</label>
                    <input type="text" class="form-control" name="carrera" oninput='validarLetras(this)' required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="turno" class="form-label">Turno:</label>
                    <select name="turno" class="form-control" required>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="rol" class="form-label">Rol:</label>
                    <select name="rol" class="form-control" required>
                        <option value="personal">Personal</option>
                        <option value="alumno">Alumno</option>
                        <option value="docente">Docente</option>
                        <option value="directivo">Directivo</option>
                        <option value="administrativo">Administrativo</option>
                        <option value="invitado">Invitado</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="action" value="Agregar" class="btn btn-success">Agregar</button>
        </form>
    </div>

    <!-- Scripts de validación -->
    <script>
        function validarNumerico(input) {
            var regex = /^[0-9]*$/;
            var valor = input.value;
            if (!regex.test(valor)) {
                input.value = valor.replace(/[^\d]/g, '');
            }
        }
        function validarLetras(input) {
            var regex = /^[A-Za-z\s]*$/;
            var valor = input.value;
            if (!regex.test(valor)) {
                input.value = valor.replace(/[^A-Za-z\s]/g, '');
            }
        }
    </script>
</body>
</html>
