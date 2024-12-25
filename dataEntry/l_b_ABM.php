<?php
session_start();
require_once ('../includes/db.php');

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $secret = '6Lfu6SsqAAAAAL6rzkxZh-6Uy6RMrYgDUGN41KRQ'; // Clave secreta para reCAPTCHA
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    // Verificar reCAPTCHA con el servidor de Google
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}&remoteip={$remoteip}");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        // El reCAPTCHA no fue resuelto correctamente
        header("Location: ../dataEntry/failEntry.php"); // Redirige a la página de fallo
        exit;
    }
    else{
        // Consulta para verificar las credenciales del usuario
        $query = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Obtiene los datos del usuario

            // Verifica la contraseña
            //if (password_verify($password, $user['contraseña'])) {
                // Verificar si el usuario tiene el rol de 'directivo' o 'administrativo'
                
                if ($user['rol'] == 'directivo' || $user['rol'] == 'administrativo') {
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario'] = $user['usuario'];
                    $_SESSION['rol'] = $user['rol'];
                    header("Location: ../dataEntry/fABM.php");
                } else {
                    //echo "Acceso denegado. Sólo los usuarios con rol 'directivo' o 'administrativo' pueden ingresar.";
                    header("Location: ../dataEntry/failRol.php"); // Redirige a la página de fallo
                }
            //} //else {
                //echo "Contraseña incorrecta.";
            //}
        } else {
            //echo "Usuario y/o contraseña incorrecta.";
            header("Location: ../dataEntry/failEntry.php"); // Redirige a la página de fallo
        }

        $stmt->close();
    }
}

$conn->close();
?>
