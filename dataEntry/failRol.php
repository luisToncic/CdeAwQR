<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo de Gestión Beltrán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/lABM.css">
</head>
<body>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="logo">
                        <img src="../assets/logo-removebg-preview.png" alt="logo" style="max-width: 100px;">
                    </div>
                    <h2 class="mt-3 mb-4 text-white">ACCESO DENEGADO. Sólo los usuarios con rol 'directivo' o 'administrativo' pueden ingresar.</h2>
                </div>
            </div>
        </div>
    </div>

<?php include_once('../includes/footer.php'); ?>

<script>
    // Redirigir a la página de inicio después de 5 segundos
    setTimeout(function() {
        window.location.href = '../gestion.php';
    }, 3000); // 5000 milisegundos = 5 segundos
</script>
