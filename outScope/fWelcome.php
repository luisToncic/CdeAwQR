<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Acceso Beltrán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<style>
    body {
        background-color: green;
    }
    
    .login-box {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box text-center">
                <div class="logo">
                    <img src="../assets/logo-removebg-preview.png" alt="logo" style="max-width: 100px;">
                </div>
                <h2 class="mt-3 mb-4">Bienvenido</h2>
                <img src="../assets/foto3.png" alt="foto3" style="max-width: 100%;">
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>

<script>
    // Redirigir a la página de inicio después de 5 segundos
    setTimeout(function() {
        window.location.href = '../index.php';
    }, 5000); // 5000 milisegundos = 5 segundos
</script>
