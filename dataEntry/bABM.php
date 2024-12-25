<?php
include '../includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function showAlertAndRedirect($message) {
    echo "<script type='text/javascript'>alert('$message'); window.location.href = 'fABM.php';</script>";
}

function registrarAuditoria($conn, $idUsuario, $accion, $descripcion) {
    $query = "INSERT INTO auditoria (idUsuario, accion, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $idUsuario, $accion, $descripcion);
    $stmt->execute();
    $stmt->close();
}

session_start();
$idUsuario = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_type = $_POST['form_type'];

    if ($form_type === 'personas') {
        $action = $_POST['action'];

        if ($action === 'Agregar') {
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
            $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
            $edad = mysqli_real_escape_string($conn, $_POST['edad']);
            $dni = mysqli_real_escape_string($conn, $_POST['dni']);
            $mail = mysqli_real_escape_string($conn, $_POST['mail']);
            $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
            $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
            $localidad = mysqli_real_escape_string($conn, $_POST['localidad']);
            $legajo = mysqli_real_escape_string($conn, $_POST['legajo']);
            $carrera = mysqli_real_escape_string($conn, $_POST['carrera']);
            $turno = mysqli_real_escape_string($conn, $_POST['turno']);
            $rol = mysqli_real_escape_string($conn, $_POST['rol']);
            $estado = 'fuera'; // Estado por defecto

            // Verificar si el DNI ya existe
            $sql = "SELECT * FROM personas WHERE dni = '$dni'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                showAlertAndRedirect("Error: El DNI ya existe.");
            } else {
                // Insertar en la tabla personas
                $sql = "INSERT INTO personas (nombre, apellido, edad, dni, mail, telefono, direccion, localidad, legajo, carrera, turno) 
                        VALUES ('$nombre', '$apellido', '$edad', '$dni', '$mail', '$telefono', '$direccion', '$localidad', '$legajo', '$carrera', '$turno')";

                if ($conn->query($sql) === TRUE) {
                    // Crear usuario automáticamente
                    $usuario = strtolower($nombre . '.' . $apellido);
                    $contraseña = 'Beltran*';
                    
                    $sql_usuario = "INSERT INTO usuarios (usuario, contraseña, rol, estado, legajo) 
                                    VALUES ('$usuario', '$contraseña', '$rol', '$estado', '$legajo')";

                    if ($conn->query($sql_usuario) === TRUE) {
                        // Registrar acción en auditoría
                        registrarAuditoria($conn, $idUsuario, 'Agregar Persona', "Persona agregada: $nombre $apellido, DNI: $dni");
                        showAlertAndRedirect("Nueva persona y usuario agregados correctamente.");
                    } else {
                        showAlertAndRedirect("Error al agregar el usuario: " . $conn->error);
                    }
                } else {
                    showAlertAndRedirect("Error al agregar la persona: " . $conn->error);
                }
            }
        } elseif ($action === 'Eliminar') {
            if (isset($_POST['person_id'])) {
                $person_id = mysqli_real_escape_string($conn, $_POST['person_id']);

                // Verificar si la persona tiene un usuario asociado
                $sql_check = "SELECT * FROM usuarios WHERE legajo = (SELECT legajo FROM personas WHERE id = $person_id) AND deleted = 0";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    showAlertAndRedirect("Persona con usuario asociado. Debe primero borrar al usuario, para poder borrar a la persona.");
                } else {
                    // Obtener información de la persona antes de eliminarla
                    $sql_info = "SELECT nombre, apellido FROM personas WHERE id = $person_id";
                    $result_info = $conn->query($sql_info);
                    $persona_info = $result_info->fetch_assoc();
                    
                                // Eliminar persona
                                //$sql = "DELETE FROM personas WHERE id = $person_id";
                    // Actualizar el campo `deleted` para marcar el usuario como eliminado
                    $sql = "UPDATE personas SET deleted = 1 WHERE id = $person_id";

                    if ($conn->query($sql) === TRUE) {
                        // Registrar acción en auditoría
                        registrarAuditoria($conn, $idUsuario, 'Eliminar Persona', "Persona eliminada: " . $persona_info['nombre'] . " " . $persona_info['apellido']);
                        showAlertAndRedirect("Persona eliminada correctamente.");
                    } else {
                        showAlertAndRedirect("Error al eliminar la persona: " . $conn->error);
                    }
                }
            }
        }
    } elseif ($form_type === 'usuarios') {
        $action = $_POST['action'];

        if ($action === 'Eliminar') {
            if (isset($_POST['user_id'])) {
                $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

                // Obtener información del usuario antes de eliminarlo
                $sql_info = "SELECT usuario FROM usuarios WHERE id = $user_id";
                $result_info = $conn->query($sql_info);
                $usuario_info = $result_info->fetch_assoc();

                // Actualizar el campo `deleted` para marcar el usuario como eliminado
                $sql = "UPDATE usuarios SET deleted = 1 WHERE id = $user_id";

                if ($conn->query($sql) === TRUE) {
                    registrarAuditoria($conn, $idUsuario, 'Eliminar Usuario', "Usuario eliminado: " . $usuario_info['usuario']);
                    showAlertAndRedirect("Usuario eliminado correctamente.");
                } else {
                    error_log("Error al eliminar el usuario: " . $conn->error);
                    showAlertAndRedirect("Error al eliminar el usuario.");
                }
            }
        }
    }
}

$conn->close();
?>
