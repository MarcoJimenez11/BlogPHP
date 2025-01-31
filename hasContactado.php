<?php
session_start();
//Capturo los datos del formulario
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nombre = $_POST['nombre_contacto'];
    $email = $_POST['email_contacto'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muchas Gracias</title>
</head>
<body>
    <h2>Gracias por contactar con nosotros, <?php echo $nombre ?></h2>
    <p>En breve nos pondremos en contacto contigo a través de tu correo: <?php echo"<strong>" . $email . "</strong>" ?></p>
    <a href="index.php">Volver al inicio</a>
</body>
</html>