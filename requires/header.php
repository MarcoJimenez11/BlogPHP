<?php
require_once '../requires/conexion.php';
require_once './metodosExternos/conseguirCategorias.php';

?>

<header>
    <h1>Blog de Videojuegos</h1>
    <nav>
        <ul>
            <li><a href="../index.php">Inicio</a></li>
            <?php
            $categorias = conseguirCategorias($db);
            foreach ($categorias as $categoria):
            ?>
                <li>
                    <a href="listarEntradasCategoria.php?id=<?= $categoria['id']?>"><?= htmlspecialchars($categoria['nombre']) ?></a>
                </li>
            <?php endforeach; ?>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>
</header>