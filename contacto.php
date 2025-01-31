<?php
session_start();
require_once 'requires/conexion.php';
require_once './metodos/metodosExternos/conseguirCategorias.php';


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="assets/css/estilo_contacto.css">
</head>

<body>
    <!-- Menú de navegación -->
    <header>
        <h1>Blog de Videojuegos</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <?php
                // Obtener categorías dinámicamente
                $categorias = conseguirCategorias($db);
                foreach ($categorias as $categoria):
                ?>
                    <li value="<?= $categoria['id'] ?>">
                        <a href="#"><?= htmlspecialchars($categoria['nombre']) ?></a>
                    </li>
                <?php endforeach; ?>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <!--Formulario-->
    <main>
        <h2>Ponte en contacto con nosotros</h2>
        <form method="POST" action="hasContactado.php">
            <input type="text" name="nombre_contacto" id="nombre_contacto" placeholder="Nombre" required><br>
            <input type="email" name="email_contacto" id="email_contacto" placeholder="Email" required><br>
            <textarea name="mensaje_contacto" placeholder="Escríbenos tu mensaje" required></textarea><br>
            <input type="submit" name="boton_contacto" id="boton_contacto" value="Enviar">
        </form>
    </main>
</body>

</html>