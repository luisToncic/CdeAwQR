<?php include_once('../includes/header.php'); ?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box text-center">
                <div class="logo">
                    <img src="../assets/logo-removebg-preview.png" alt="logo" style="max-width: 100px;">
                </div>
                <h2 class="mt-3 mb-4 text-white">usuario ó contraseña INCORRECTOS</h2>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>

<script>
    // Redirigir a la página de inicio después de 5 segundos
    setTimeout(function() {
        window.location.href = '../index.php';
    }, 2000); // 5000 milisegundos = 5 segundos
</script>
