<?php
session_start();
require_once '../requires/conexion.php';

try {
    if (isset($_GET["id"]) && $_GET["id"] != null) {
        $categoria = $_GET["id"];
        $sql = "SELECT e.id, e.titulo, e.descripcion, c.nombre AS categoria, 
                u.nombre AS autor, u.id AS usuario_id, e.fecha 
                FROM entradas e
                INNER JOIN categorias c ON e.categoria_id = c.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                WHERE e.categoria_id = :categoria
                ORDER BY e.fecha DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
    } else if (isset($_POST["buscarTitulo"]) && $_POST["buscarTitulo"] != null) {
        $titulo = $_POST["buscarTitulo"];
        $sql = "SELECT e.id, e.titulo, e.descripcion, c.nombre AS categoria, 
                   u.nombre AS autor, u.id AS usuario_id, e.fecha 
            FROM entradas e
            INNER JOIN categorias c ON e.categoria_id = c.id
            INNER JOIN usuarios u ON e.usuario_id = u.id
            WHERE e.titulo LIKE '%$titulo%'
            ORDER BY e.fecha DESC";

        $stmt = $db->prepare($sql);
        // $stmt->bindParam(':titulo', $titulo, PDO::PARAM_INT);
    } else {
        $sql = "SELECT e.id, e.titulo, e.descripcion, c.nombre AS categoria, 
                   u.nombre AS autor, u.id AS usuario_id, e.fecha 
                FROM entradas e
                INNER JOIN categorias c ON e.categoria_id = c.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                ORDER BY e.fecha DESC";
        $stmt = $db->prepare($sql);
    }
    $stmt->execute();
    $entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener las entradas: " . $e->getMessage());
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
    <section class="contentEntrada">
        <h1 class="h1Entrada">Listado de entradas</h1>
        <?php if (!empty($entradas)): ?>
            <table class="tablaEntrada">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entradas as $entrada): ?>
                        <tr>
                            <td>
                                <div class="flex-container">
                                    <?= htmlspecialchars($entrada['titulo']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex-container">
                                    <?= htmlspecialchars(substr($entrada['descripcion'], 0, 100)) ?>...
                                </div>
                            </td>
                            <td>
                                <div class="flex-container">
                                    <?= htmlspecialchars($entrada['categoria']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex-container">
                                    <?= htmlspecialchars($entrada['autor']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex-container">
                                    <?= htmlspecialchars($entrada['fecha']) ?>
                                </div>
                            </td>
                            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['id'] == $entrada['usuario_id']): ?>
                                <td>
                                    <div class="flex-container-acciones">
                                        <a class="botonEntrada" href="./editarEntradas.php?id=<?= $entrada['id'] ?>">Editar</a>
                                        <a class="botonEntrada" href="./borrarEntradas.php?id=<?= $entrada['id'] ?>" onclick="return confirm('¿Estás seguro de querer borrar esta entrada?')">Borrar</a>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php else: ?>
            <p>No hay entradas disponibles.</p>
        <?php endif; ?>
    </section>
</body>

</html>