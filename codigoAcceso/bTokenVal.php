<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el token enviado desde el formulario
    $token = $_POST['token'] ?? '';

    // Verificar si el token fue enviado correctamente
    if (empty($token)) {
        echo "Error: El token no fue proporcionado.";
        exit();
    }

    // Mostrar el token recibido para verificarlo
    echo "Token recibido: " . htmlspecialchars($token) . "<br>";

    // Preparar la consulta para verificar el token en la base de datos
    $query = "SELECT * FROM tokens WHERE token = ? AND fecha_expiracion > NOW() AND usado = 0";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        // Mostrar error si la preparación de la consulta falla
        echo "Error en la consulta de verificación del token: " . $conn->error;
        exit();
    }

    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token válido
        $row = $result->fetch_assoc();
        $usuario_id = $row['usuario_id'];
        $token_id = $row['id']; // Asumimos que 'id' es el 'token_id'

        echo "Token válido. Usuario ID: " . $usuario_id . "<br>";
        echo "Token ID: " . $token_id . "<br>";

         // Actualizar el token a 'usado'
        $query_usado = "UPDATE tokens SET usado = 1 WHERE id = ?";
        $stmt_usado = $conn->prepare($query_usado);

        if (!$stmt_usado) {
            echo "Error al actualizar el estado del token: " . $conn->error;
            exit();
        }

        $stmt_usado->bind_param("i", $token_id);
        $stmt_usado->execute();



        // Verificar el estado actual del usuario
        $query_estado = "SELECT estado FROM usuarios WHERE id = ?";
        $stmt_estado = $conn->prepare($query_estado);

        if (!$stmt_estado) {
            echo "Error en la consulta del estado del usuario: " . $conn->error;
            exit();
        }

        $stmt_estado->bind_param("i", $usuario_id);
        $stmt_estado->execute();
        $result_estado = $stmt_estado->get_result();

        if ($result_estado->num_rows > 0) {
            $user = $result_estado->fetch_assoc();
            $estado_actual = $user['estado'];

            echo "Estado actual del usuario: " . $estado_actual . "<br>";

            if ($estado_actual == 'fuera') {
                // Cambiar el estado a 'dentro' y registrar el ingreso
                $query_update = "UPDATE usuarios SET estado = 'dentro' WHERE id = ?";
                $stmt_update = $conn->prepare($query_update);

                if (!$stmt_update) {
                    echo "Error al actualizar el estado del usuario: " . $conn->error;
                    exit();
                }

                $stmt_update->bind_param("i", $usuario_id);
                $stmt_update->execute();

                // Registrar el ingreso en bitacoraAccesos
                $query_acceso = "INSERT INTO bitacoraAccesos (idUsuario, token_id, fechaIngreso) VALUES (?, ?, NOW())";
                $stmt_acceso = $conn->prepare($query_acceso);

                if (!$stmt_acceso) {
                    echo "Error al registrar el ingreso en la bitácora: " . $conn->error;
                    exit();
                }

                $stmt_acceso->bind_param("ii", $usuario_id, $token_id); // Pasamos token_id también
                $stmt_acceso->execute();

                // Redirigir a fWelcome.php
                echo "Usuario ahora está 'dentro'. Redirigiendo...";
                header("Location: ../outScope/fWelcome.php");
                exit();

            } else {
                // Cambiar el estado a 'fuera' y registrar el egreso
                $query_update = "UPDATE usuarios SET estado = 'fuera' WHERE id = ?";
                $stmt_update = $conn->prepare($query_update);

                if (!$stmt_update) {
                    echo "Error al actualizar el estado del usuario: " . $conn->error;
                    exit();
                }

                $stmt_update->bind_param("i", $usuario_id);
                $stmt_update->execute();

                // Registrar el egreso en la bitácora
                $query_acceso = "UPDATE bitacoraAccesos SET fechaEgreso = NOW() WHERE idUsuario = ? ORDER BY fechaIngreso DESC LIMIT 1";
                $stmt_acceso = $conn->prepare($query_acceso);

                if (!$stmt_acceso) {
                    echo "Error al registrar el egreso en la bitácora: " . $conn->error;
                    exit();
                }

                $stmt_acceso->bind_param("i", $usuario_id);
                $stmt_acceso->execute();

                // Redirigir a fOut.php
                echo "Usuario ahora está 'fuera'. Redirigiendo...";
                header("Location: ../outScope/fOut.php");
                exit();
            }
        } else {
            echo "Error: No se encontró el estado del usuario.";
        }
    } else {
        // Token no válido o expirado
        echo "Token inválido o expirado. Redirigiendo a la página de error.";
        header("Location: ../outScope/fError.php");
        exit();
    }
} else {
    echo "Método no permitido. Solo se permite POST.";
    header("Location: ../outScope/fError.php");
    exit();
}

// Cerrar las conexiones
$stmt->close();
$conn->close();
?>
