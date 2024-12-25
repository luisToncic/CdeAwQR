function sendMail(email) {
    // Crear el cuerpo del correo
    const subject = "Contacto desde la página de créditos";
    const body = "Hola,\n\nMe gustaría ponerme en contacto contigo.\n\nSaludos.";

    // Crear un formulario invisible y enviarlo al servidor PHP para procesar el envío del correo
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'send_mail.php'; // Archivo PHP que procesará el envío del correo

    const inputEmail = document.createElement('input');
    inputEmail.type = 'hidden';
    inputEmail.name = 'email';
    inputEmail.value = email;

    const inputSubject = document.createElement('input');
    inputSubject.type = 'hidden';
    inputSubject.name = 'subject';
    inputSubject.value = subject;

    const inputBody = document.createElement('input');
    inputBody.type = 'hidden';
    inputBody.name = 'body';
    inputBody.value = body;

    form.appendChild(inputEmail);
    form.appendChild(inputSubject);
    form.appendChild(inputBody);

    document.body.appendChild(form);
    form.submit();
}
