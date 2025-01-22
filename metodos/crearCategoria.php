<?php
session_start();
require_once '../requires/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = [];
    $nombre = trim($_POST['nombreCategoria'] ?? '');

    // Validar el nombre
    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre de la categoría es obligatorio.';
    } elseif (strlen($nombre) > 50) {
        $errores['nombre'] = 'El nombre de la categoría no puede exceder los 50 caracteres.';
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO categorias (nombre) VALUES (:nombre)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->execute();

            // Mostramos un mensaje de exito que dure 2 segundos, que se cierre solo y redirijimos a index.php
            echo '<div class="mensaje-exito">Categoría creada con éxito</div>';
            header("refresh:2;url=../index.php");
            exit;
        } catch (PDOException $e) {
            $errores['general'] = 'Error al crear la categoría: ' . $e->getMessage();
        }
    }
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Entradas</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>

<body>
    <?php require_once "../requires/header.php" ?>

    <h1>Crear Categoría nueva</h1>

    <form method="POST" action="crearCategoria.php">
        <label for="nombreCategoria">Nombre de la categoría:</label>
        <input type="text" name="nombreCategoria" placeholder="Nombre">
        <button type="submit" name="botonRegistro">Crear Categoría</button>
    </form>
    </section>
</body>

</html>



