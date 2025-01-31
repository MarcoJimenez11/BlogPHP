<?php
session_start();
require_once '../requires/conexion.php';
require_once "../requires/header.php";

$errores = [];
$exito = "";

// Procesar actualización de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['botonActualizar'])) {
    $idUsuario = $_SESSION['usuario']['id'];
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);

    // Validar los datos
    if (empty($nombre) || empty($apellidos) || empty($email)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no tiene un formato válido.";
    }

    if (empty($errores)) {
        try {
            // Comprobar si el email ya existe para otro usuario
            $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $idUsuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $errores[] = "El email ya está en uso por otro usuario.";
            } else {
                // Actualizar los datos del usuario
                $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email WHERE id = :id");
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellidos', $apellidos);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':id', $idUsuario);

                if ($stmt->execute()) {
                    // Actualizar la información en la sesión
                    $_SESSION['usuario']['nombre'] = $nombre;
                    $_SESSION['usuario']['apellidos'] = $apellidos;
                    $_SESSION['usuario']['email'] = $email;
                    $exito = "Datos actualizados con éxito.";
                    header("Location: ../index.php");
                    exit();
                } else {
                    $errores[] = "Error al actualizar los datos.";
                }
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>

<body>
    <main>
        <section>
            <?php if (!empty($exito)): ?>
                <span style="color: green;"><?= $exito ?></span>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div style="color: red;">
                    <?php foreach ($errores as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h2>Actualiza tus datos</h2>
            <form method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?>" required>
                <br>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($_SESSION['usuario']['apellidos'] ?? '') ?>" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?>" required>
                <br>
                <button type="submit" name="botonActualizar">Actualizar</button>
            </form>
        </section>
    </main>
</body>

</html>
