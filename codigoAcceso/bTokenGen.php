<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Generar un token único
$token = bin2hex(random_bytes(16)); // Genera un token aleatorio
$fecha_creacion = date('Y-m-d H:i:s'); // Fecha y hora actual
$fecha_expiracion = date('Y-m-d H:i:s', strtotime('+1 hour')); // Fecha y hora de expiración (1 hora desde ahora)

//$fecha_expiracion = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Fecha y hora de expiración (5 minutos desde ahora)
//$fecha_expiracion = date('Y-m-d H:i:s', strtotime('+30 seconds')); // Fecha y hora de expiración (30 segundos desde ahora)

// Insertar el token en la base de datos
$query = "INSERT INTO tokens (usuario_id, token, fecha_creacion, fecha_expiracion) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $usuario_id, $token, $fecha_creacion, $fecha_expiracion);

if ($stmt->execute()) {
    // Redirigir a la generación del QR Code con el ID del token recién creado
    $token_id = $stmt->insert_id;
    header("Location: bQRGen.php?token_id=" . $token_id);
} else {
    echo "Error al generar el token.";
}

$stmt->close();
$conn->close();
?>
