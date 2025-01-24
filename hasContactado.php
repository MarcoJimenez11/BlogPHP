<?php
session_start();
//Capturo los datos del formulario
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_SESSION['nombre_contacto'] = $_POST['nombre_contacto'];
    $_SESSION['email_contacto'] = $_POST['email_contacto'];
}
//TENGO QUE PONER EL MENÚ ARRIBA
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muchas Gracias</title>
</head>
<body>
    <h2>Gracias por contactar con nosotros, <?php echo $_SESSION['nombre_contacto'] ?></h2>
    <p>En breve nos pondremos en contacto contigo a través de tu correo: <?php echo"<strong>" . $_SESSION['email_contacto'] . "</strong>" ?></p>
    <a href="index.php">Volver al inicio</a>
</body>
</html>