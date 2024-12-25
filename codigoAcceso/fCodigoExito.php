<?php include_once('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box text-center">
                <div class="logo">
                    <img src="../assets/logo-removebg-preview.png" alt="logo" style="max-width: 100px;">
                </div>
                <h2 class="mt-3 mb-4 text-white">Bienvenido</h2>
                <?php
                $qr_code_path = urldecode($_GET['qr_code_path']);
                $unique_id = time(); // Usar la marca de tiempo actual para hacerlo único
                echo '<img src="' . $qr_code_path . '?' . $unique_id . '" alt="QR Code" style="max-width: 100%;">';
                ?>
                <!-- <img src="../assets/foto3.png" alt="foto3" style="max-width: 100%;"> -->
                <!-- botón para salir sino 2 min abajo se modificaria -->
                <div class="mt-4">
                    <a href="../index.php" class="btn btn-primary">Salir</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>

<script>
    // Redirigir a la página de inicio después de 5 segundos
    setTimeout(function() {
        window.location.href = '../index.php';
    }, 121000); // 5000 milisegundos = 5 segundos
</script>
