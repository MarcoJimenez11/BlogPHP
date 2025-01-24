<?php
// 1. Iniciamos sesión
session_start();

// Requires
require_once 'requires/conexion.php';
require_once './metodos/metodosExternos/conseguirUltimasEntradas.php';

// Llamamos a la funcion universal creada conseguirUltimasEntradas, para obtener las últimas entradas
$ultimasEntradas = conseguirUltimasEntradas($db, 5);

$_SESSION['loginExito'] = $_SESSION['loginExito'] ?? false;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog de Videojuegos</title>
    <link rel="stylesheet" href="./assets/css/estilo.css">
</head>

<body>
    <header>
        <h1>Blog de Videojuegos</h1>
        <nav>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Acción</a></li>
                <li><a href="#">Rol</a></li>
                <li><a href="#">Deportes</a></li>
                <li><a href="#">Responsabilidad</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="content">
            <h2>Últimas entradas</h2>
            <!-- Probando el array que me trae $ultimasEntradas -->
            <!-- <?= var_dump($ultimasEntradas)?> -->
            <?php if (!empty($ultimasEntradas)): ?>
                <?php foreach ($ultimasEntradas as $entradaIndividual): ?>
                    <article class ="ultimasEntradas">
                        <h3><?= htmlspecialchars($entradaIndividual["titulo"]) ?></h3>
                        <span class="categoria">Categoría: <?= htmlspecialchars($entradaIndividual["categoria"]) ?> | Fecha: <?= htmlspecialchars($entradaIndividual["fecha"]) ?></span>
                        <p>Descripcion: <?= htmlspecialchars($entradaIndividual["descripcion"]) ?></p>
                        <a href="./metodos/verDetalleEntrada.php?id=<?= $entradaIndividual["id_entrada"] ?>" class="botonLink">Leer más</a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se han encontrado entradas</p>
            <?php endif; ?>
            <form action="./metodos/listarTodasEntradas.php" method="post">
                <button>Ver todas las entradas</button>
            </form>
        </section>
        <aside>
            <div class="search">
                <h3>Buscar</h3>
                <input type="text" placeholder="Buscar...">
                <button>Buscar</button>
            </div>
            <?php if (!$_SESSION['loginExito']) { ?>
                <div class="login">
                    <h3>Identificate</h3>
<<<<<<< HEAD
                    <?php if (isset($_SESSION['errorPassLogin']))
                        echo $_SESSION['errorPassLogin']; ?>
                    <form method="POST" action="login.php">
                        <input type="email" name="emailLogin" placeholder="Email">
                        <input type="password" name="passwordLogin" placeholder="Contraseña">
                        <button type="submit" name="botonLogin">Entrar</button>
                    </form>
                </div>
                <div class="register">
                    <h3>Registrate</h3>
                    <?php if (isset($_SESSION['success_message']))
                        echo $_SESSION['success_message']; ?>
                    <form method="POST" action="registro.php">
                        <input type="text" name="nombreRegistro" placeholder="Nombre">
                        <input type="text" name="apellidosRegistro" placeholder="Apellidos">
                        <input type="email" name="emailRegistro" placeholder="Email">
                        <input type="password" name="passwordRegistro" placeholder="Contraseña">
                        <button type="submit" name="botonRegistro">Registrar</button>
                    </form>
                </div>
            <?php } else { ?>
                <div>
                    <form method="POST" action="logout.php">
                        <button type="submit" name="botonCerrarSesion">Cerrar Sesión</button>
                    </form>
                    <form method="POST" action="./metodos/crearEntradas.php">
                        <button type="submit" name="crearEntrada">Crear Entrada</button>
                    </form>
                </div>
            <?php } ?>
||||||| 93cd55c
                    <?php
                    if (isset($_SESSION['errorEmailLogin'])) {
                        echo '<span style="color: red;">' . $_SESSION['errorEmailLogin'] . '</span>';
                    } else {
                        if (isset($_SESSION['errorPassLogin'])) {
                            echo '<span style="color: red;">' . $_SESSION['errorPassLogin'] . '</span>';
                        }
                    } ?>
                    <div class="login">
                        <h3>Identificate</h3>
                        <?php if (isset($_SESSION['errorPassLogin']))
                            echo $_SESSION['errorPassLogin']; ?>
                        <form method="POST" action="login.php">
                            <input type="email" name="emailLogin" placeholder="Email">
                            <input type="password" name="passwordLogin" placeholder="Contraseña">
                            <button type="submit" name="botonLogin">Entrar</button>
                        </form>
                    </div>
                    <div class="register">
                        <h3>Registrate</h3>
                        <?php if (isset($_SESSION['success_message']))
                            echo $_SESSION['success_message']; ?>
                        <form method="POST" action="registro.php">
                            <input type="text" name="nombreRegistro" placeholder="Nombre">
                            <input type="text" name="apellidosRegistro" placeholder="Apellidos">
                            <input type="email" name="emailRegistro" placeholder="Email">
                            <input type="password" name="passwordRegistro" placeholder="Contraseña">
                            <button type="submit" name="botonRegistro">Registrar</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div>
                        <form method="POST" action="metodos/actualizarDatosUsuario.php">
                            <button type="submit" name="actualizarDatosUsuario.php">Editar datos de usuario</button>
                        </form>
                        <form method="POST" action="logout.php">
                            <button type="submit" name="botonCerrarSesion">Cerrar Sesión</button>
                        </form>
                        <form method="POST" action="metodos/crearCategoria.php">
                            <button type="submit" name="botonCrearCategoria">Crear Categoría</button>
                        </form>
                    </div>
                <?php } ?>
=======
                    <?php
                    if (isset($_SESSION['errorEmailLogin'])) {
                        echo '<span style="color: red;">' . $_SESSION['errorEmailLogin'] . '</span>';
                    } else {
                        if (isset($_SESSION['errorPassLogin'])) {
                            echo '<span style="color: red;">' . $_SESSION['errorPassLogin'] . '</span>';
                        }
                    } ?>
                    <div class="login">
                        <h3>Identificate</h3>
                        <?php if (isset($_SESSION['errorPassLogin']))
                            echo $_SESSION['errorPassLogin']; ?>
                        <form method="POST" action="login.php">
                            <input type="email" name="emailLogin" placeholder="Email">
                            <input type="password" name="passwordLogin" placeholder="Contraseña">
                            <button type="submit" name="botonLogin">Entrar</button>
                        </form>
                    </div>
                    <div class="register">
                        <h3>Registrate</h3>
                        <?php if (isset($_SESSION['success_message']))
                            echo $_SESSION['success_message']; ?>
                        <form method="POST" action="registro.php">
                            <input type="text" name="nombreRegistro" placeholder="Nombre">
                            <input type="text" name="apellidosRegistro" placeholder="Apellidos">
                            <input type="email" name="emailRegistro" placeholder="Email">
                            <input type="password" name="passwordRegistro" placeholder="Contraseña">
                            <button type="submit" name="botonRegistro">Registrar</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div>
                        <form method="POST" action="metodos/actualizarDatosUsuario.php">
                            <button type="submit" name="actualizarDatosUsuario.php">Editar datos de usuario</button>
                        </form>
                        <form method="POST" action="logout.php">
                            <button type="submit" name="botonCerrarSesion">Cerrar Sesión</button>
                        </form>
                        <form method="POST" action="metodos/crearCategoria.php">
                            <button type="submit" name="botonCrearCategoria">Crear Categoría</button>
                        </form>
                    </div>
                <?php } ?>
>>>>>>> Actualizar_Datos_Usuario_Pedro

        </aside>
    </main>
</body>

</html>