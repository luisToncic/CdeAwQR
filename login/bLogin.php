<?php
session_start(); // Inicio de sesión para manejar variables de sesión
require_once ('../includes/db.php'); // Incluye el archivo de conexión a la base de datos

// Obtener los datos del formulario
$email = $_POST['email']; // Captura el email ingresado por el usuario
$password = $_POST['password']; // Captura la contraseña ingresada por el usuario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $secret = '6Lfu6SsqAAAAAL6rzkxZh-6Uy6RMrYgDUGN41KRQ'; // Clave secreta para reCAPTCHA
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    // Verificar reCAPTCHA con el servidor de Google
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}&remoteip={$remoteip}");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        // El reCAPTCHA no fue resuelto correctamente
        header("Location: ../codigoAcceso/fCodigoFail.php");
        exit;
    } else {
        // Consulta para verificar las credenciales del usuario
        $query = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ? AND deleted = 0"; // Consulta SQL para buscar el usuario por email y contraseña
        $stmt = $conn->prepare($query); // Prepara la consulta para evitar inyecciones SQL
        $stmt->bind_param("ss", $email, $password); // Vincula los parámetros usuario y contraseña a la consulta
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene el resultado de la consulta

        if ($result->num_rows > 0) { // Si se encuentra al menos una fila
            $user = $result->fetch_assoc(); // Obtiene los datos del usuario

            // Credenciales correctas, inicio de sesión exitoso
            $_SESSION['usuario_id'] = $user['id']; // Almacena el ID del usuario en la sesión
            header("Location: ../codigoAcceso/bTokenGen.php"); // Redirige a la generación del token
            
        } else {
            // Email o contraseña incorrectos
            header("Location: ../codigoAcceso/fCodigoFail.php"); // Redirige a la página de fallo
        }

        $stmt->close(); // Cierra la declaración preparada
    }
}

$conn->close(); // Cierra la conexión a la base de datos
?>
