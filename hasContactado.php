<?php
session_start();
require_once 'requires/conexion.php';
require_once './metodos/metodosExternos/conseguirCategorias.php';



//Capturo los datos del formulario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Validar email
    $email_contacto = filter_var(trim($_POST['email_contacto']), FILTER_VALIDATE_EMAIL);
    var_dump($_POST['nombre_contacto']);

    if ($email_contacto) {
        //Sanitizar datos (esto siempre es bueno hacerlo)
        $nombre_contacto = htmlspecialchars(trim($_POST['nombre_contacto']));
        $descripcion_contacto = htmlspecialchars(trim($_POST['mensaje_contacto']));

        // Insertar datos en la base de datos sin verificar si el email ya existe
        $stmt = $db->prepare("INSERT INTO contacto (Nombre, Email, Descripcion) VALUES (:nombre_contacto, :email_contacto, :descripcion_contacto)");
        $stmt->bindParam(':nombre_contacto', $nombre_contacto);
        $stmt->bindParam(':email_contacto', $email_contacto);
        $stmt->bindParam(':descripcion_contacto', $descripcion_contacto);
        $stmt->execute();

        // Redirigir al usuario a hasContactado.php después de enviar el formulario
        $_SESSION['nombre_contacto'] = $nombre_contacto;
        $_SESSION['email_contacto'] = $email_contacto;
        header("Location: hasContactado.php");
        exit();
    } else {
        echo "<p style='color: red;'>Por favor, introduce un email válido.</p>";
    }
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
    <h2>Gracias por contactar con nosotros, <?php echo"<strong>" . $_SESSION['nombre_contacto'] . "</strong>" ?> </h2>
    <p>En breve nos pondremos en contacto contigo a través de tu correo: <?php echo"<strong>" . $_SESSION['email_contacto'] . "</strong>" ?></p>
    <a href="index.php">Volver al inicio</a>
</body>
</html>