<?php
require_once('../includes/db.php');
require_once('../dependencies/phpqrcode/qrlib.php'); // Incluye la biblioteca phpqrcode

// Definir la URL base
$prefixUrl = "http://44.212.37.154/outScope/ingresar.php";

// Obtener el ID del token de la URL
$token_id = $_GET['token_id'];

// Obtener el token de la base de datos
$query = "SELECT token FROM tokens WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $token_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $token = $row['token'];

    
    // Construir la URL completa con el token como parámetro
    $url = $prefixUrl . "?token=" . urlencode($token);

    // Generar el QR Code con la URL completa
    $file_path = "../assets/qr_code.png"; // Ruta para guardar el QR Code
    QRcode::png($url, $file_path, QR_ECLEVEL_L, 10); // Genera el QR Code con la URL completa
    

/*
    // Verifica si el directorio de destino no existe.
    $path = "../qrcodes/"; // Ruta para guardar los archivos QR Code
    if (!file_exists($path)) {
        // Crea el directorio de destino si no existe, con permisos de lectura, escritura y ejecución.
        mkdir($path, 0777, true);
    }

    // Construir la URL completa con el token como parámetro
    $url = $prefixUrl . "?token=" . urlencode($token);

    // Generar el QR Code con la URL completa
    $file_path = $path . "qr_code.png"; // Ruta completa para guardar el QR Code
    QRcode::png($url, $file_path, QR_ECLEVEL_L, 10); // Genera el QR Code con la URL completa
*/


    // Redirigir a la página de éxito
    header("Location: fCodigoExito.php?qr_code_path=" . urlencode($file_path));
    exit; // Es importante salir del script después de la redirección
} else {
    echo "Token no encontrado.";
}

$stmt->close();
$conn->close();
?>
