<?php include_once ('../includes/header.php'); ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg">
        <div class="card-body login-box">
            <div class="text-center mb-4 user-box">
                <img src="../assets/logo-removebg-preview.png" alt="logo" class="img-fluid" style="max-height: 150px;">
            </div>
            <form id="loginForm" action="../login/bLogin.php" method="post" onsubmit="return validateCaptcha()"> 
                <div class="mb-3 user-box">
                    <input type="text" class="form-control" id="email" name="email" required>
                    <label for="email">Ingrese nick</label>
                </div>
                <div class="mb-3 user-box">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <label for="password">Ingrese Contraseña</label>
                </div>
                <!-- reCaptcha widget -->
                <div class="mb-3 text-center">
                    <div class="g-recaptcha" data-sitekey="6Lfu6SsqAAAAAFw5j8XosM-dL4TMfIOi1nrZzQfX"></div>
                    <!-- Contenedor para mostrar el mensaje de error -->
                        <div id="captcha-error" style="color: red; font-size: 14px; margin-top: 5px;"></div>
                </div>
                <!-- /reCaptcha widget -->
                <div class="text-center">
                    <button type="submit" class="btn btn-custom btn-primary-custom">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- SCRIPT PARA USAR RECAPTCHAs -->

<script>

function validateCaptcha(event) {
    var response = grecaptcha.getResponse();
    var captchaError = document.getElementById('captcha-error');
    
    // Limpia cualquier mensaje previo
    captchaError.innerHTML = "";

    if (response.length === 0) {
        // Si el captcha no ha sido completado, mostrar el mensaje de error
        captchaError.innerHTML = "Por favor, completa el reCAPTCHA.";
        return false; // Evita el envío del formulario
    }
    
    return true; // Permite el envío del formulario si el CAPTCHA está completado
}
</script>

<?php include_once ('../includes/footer.php'); ?>
