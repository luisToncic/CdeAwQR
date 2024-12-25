<?php include_once('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box text-center">
                <div class="logo">
                    <img src="../assets/logo-removebg-preview.png" alt="logo" style="max-width: 100px;">
                </div>
                <h2 class="mt-3 mb-4 text-white">LECTURA DE QR/TOKEN</h2>
                <?php
                // Obtener el token escaneado
                $token_escaneado = $_GET['token'] ?? '';
                ?>
                <div class="mt-4 user-box">
                    <form action="../codigoAcceso/bTokenVal.php" method="post">
                    <?php
                        echo '<input type="text" name="token" class="form-control mb-3" value="' . htmlspecialchars($token_escaneado) . '" >';
                    ?>
                    <label for="">Token</label>
                    <!-- Botón "Enviar" -->
                    <button type="submit" class="btn btn-custom btn-success-custom">ENVIAR</button>
                    <br>
                    <!-- Botón "Cancelar" va a la página de inicio -->
                    <a href="../index.php" class="btn btn-custom btn-primary-custom">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
