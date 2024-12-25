<?php
include_once('../includes/db.php');

// Verifico si se envió un código
if(isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];

    // Mensaje de depuración para verificar el valor del código
    echo "<script>console.log('1Código recibido: " . $codigo . "');</script>";

    // Consulta SQL para verificar si el código existe y está activo
    $sql = "SELECT * FROM codigos WHERE codigo_acceso = '$codigo' AND estado_expirado = 0 AND baja = 0";
    $resultado = mysqli_query($conn, $sql);

    if(mysqli_num_rows($resultado) > 0) {
        
        // El código existe y está activo
        $fila = mysqli_fetch_assoc($resultado);
        $id_codigo = $fila['id'];
        $estado_codigo = $fila['estado'];

        if($estado_codigo) {
            
            // Actualizo el estado del código a FALSE en la base de datos
            $sql_update = "UPDATE codigos SET estado = FALSE WHERE id = $id_codigo";
            mysqli_query($conn, $sql_update);

            header('Location: fCodigoExitoOut.php');
            exit();
        } else {
            
            // Actualizo el estado del código a TRUE en la base de datos
            $sql_update = "UPDATE codigos SET estado = TRUE WHERE id = $id_codigo";
            mysqli_query($conn, $sql_update);

            // Redirecciono a la página de éxito
            header('Location: fCodigoExito.php');
            exit();
        }
    } else {
        
        // El código no existe o no está activo
        header('Location: fCodigoFail.php');
        exit();
    }
} else {
    
    // Si no se envió un código, redireccionar a la página de error
    header('Location: fCodigoFail.php');
    exit();
}
?>
