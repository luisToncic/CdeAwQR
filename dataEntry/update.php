<?php
include '../includes/db.php';

function registrarAuditoria($conn, $idUsuario, $accion, $descripcion) {
    $query = "INSERT INTO auditoria (idUsuario, accion, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $idUsuario, $accion, $descripcion);
    $stmt->execute();
    $stmt->close();
}

session_start();
$idUsuario = $_SESSION['usuario_id'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Registro</title>
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

        .btn-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET['type']) && isset($_GET['id'])) {
            $type = $_GET['type'];
            $id = $_GET['id'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($type == 'personas') {
                    $nombre = $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $edad = $_POST['edad'];
                    $dni = $_POST['dni'];
                    $mail = $_POST['mail'];
                    $telefono = $_POST['telefono'];
                    $direccion = $_POST['direccion'];
                    $localidad = $_POST['localidad'];
                    $legajo = $_POST['legajo'];
                    $carrera = $_POST['carrera'];
                    $turno = $_POST['turno'];

                    $sql = "UPDATE personas SET nombre='$nombre', apellido='$apellido', edad=$edad, dni='$dni', mail='$mail', telefono='$telefono', direccion='$direccion', localidad='$localidad', legajo='$legajo', carrera='$carrera', turno='$turno' WHERE id=$id";

                    if ($conn->query($sql) === TRUE) {
                        registrarAuditoria($conn, $idUsuario, 'Modificar Persona', "Persona modificada: $nombre $apellido, DNI: $dni");
                        echo '<div class="alert alert-success mt-3" role="alert">Datos actualizados exitosamente</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-3" role="alert">Error al actualizar los datos: ' . $conn->error . '</div>';
                    }
                } elseif ($type == 'usuarios') {
                    $usuario = $_POST['usuario'];
                    $contraseña = $_POST['contraseña'];
                    $rol = $_POST['rol'];
                    $estado = $_POST['estado'];
                    $legajo = $_POST['legajo'];

                    $sql = "UPDATE usuarios SET usuario='$usuario', contraseña='$contraseña', rol='$rol', estado='$estado', legajo='$legajo' WHERE id=$id";

                    if ($conn->query($sql) === TRUE) {
                        registrarAuditoria($conn, $idUsuario, 'Modificar Usuario', "Usuario modificado: $usuario, Legajo: $legajo");
                        echo '<div class="alert alert-success mt-3" role="alert">Datos actualizados exitosamente</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-3" role="alert">Error al actualizar los datos: ' . $conn->error . '</div>';
                    }
                }
            }

            $sql = "SELECT * FROM $type WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                echo "<h2 class='my-4'>Actualizar " . ucfirst($type) . "</h2>";
                echo "<form method='post'><div class='row'>";
                foreach ($row as $key => $value) {
                    $label = ucfirst($key);
                    if ($key == 'nombre' || $key == 'apellido' || $key == 'localidad') {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<label for='$key' class='form-label'>$label:</label>";
                        echo "<input type='text' class='form-control' name='$key' id='$key' value='$value' oninput='validarLetras(this)'>";
                        echo "</div>";
                    } elseif ($key == 'turno' || ($type == 'usuarios' && $key == 'rol')) {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<label for='$key' class='form-label'>$label:</label>";
                        echo "<select name='$key' class='form-control' id='$key'>";
                        if ($key == 'turno') {
                            echo "<option value='Mañana' " . ($value == 'Mañana' ? 'selected' : '') . ">Mañana</option>";
                            echo "<option value='Tarde' " . ($value == 'Tarde' ? 'selected' : '') . ">Tarde</option>";
                            echo "<option value='Noche' " . ($value == 'Noche' ? 'selected' : '') . ">Noche</option>";
                        } elseif ($key == 'rol') {
                            echo "<option value='personal' " . ($value == 'personal' ? 'selected' : '') . ">Personal</option>";
                            echo "<option value='alumno' " . ($value == 'alumno' ? 'selected' : '') . ">Alumno</option>";
                            echo "<option value='docente' " . ($value == 'docente' ? 'selected' : '') . ">Docente</option>";
                            echo "<option value='directivo' " . ($value == 'directivo' ? 'selected' : '') . ">Directivo</option>";
                            echo "<option value='administrativo' " . ($value == 'administrativo' ? 'selected' : '') . ">Administrativo</option>";
                            echo "<option value='invitado' " . ($value == 'invitado' ? 'selected' : '') . ">Invitado</option>";
                        }
                        echo "</select>";
                        echo "</div>";
                    } else {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<label for='$key' class='form-label'>$label:</label>";
                        echo "<input type='text' class='form-control' name='$key' id='$key' value='$value'>";
                        echo "</div>";
                    }
                }
                echo "</div><button type='submit' class='btn btn-success'>Actualizar</button>";
                echo "</form>";
                echo "<a href='fABM.php' class='btn btn-secondary mt-3'>Volver</a>";
            } else {
                echo '<div class="alert alert-warning mt-3" role="alert">No se encontraron datos para actualizar</div>';
            }
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Error: Tipo de entidad o ID no proporcionados</div>';
        }
        ?>
    </div>

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
