<?php

// Creamos la sesion
session_start();

// Requires
require_once "../requires/conexion.php";
require_once "./metodosExternos/obtenerEntradasPorCategoria.php";

// Obtener el ID de la entrada desde la URL
$entrada_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validar el ID de la entrada
if ($entrada_id <= 0) {
    die("Error: Entrada no válida.");
}

// Obtener los datos de la entrada
try {
    $sql = "SELECT e.id, e.titulo, e.descripcion, c.nombre AS categoria, 
                   u.nombre AS autor, e.fecha 
            FROM entradas e
            INNER JOIN categorias c ON e.categoria_id = c.id
            INNER JOIN usuarios u ON e.usuario_id = u.id
            WHERE e.id = :entrada_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':entrada_id', $entrada_id, PDO::PARAM_INT);
    $stmt->execute();
    $entrada = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$entrada) {
        die("Error: Entrada no encontrada.");
    }
} catch (PDOException $e) {
    die("Error al obtener la entrada: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($entrada['titulo']) ?></title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>

    <main>
        <article>
            <p><strong>Autor:</strong> <?= htmlspecialchars($entrada['autor']) ?></p>
            <p><strong>Categoría:</strong> <?= htmlspecialchars($entrada['categoria']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($entrada['fecha']) ?></p>
            <div class="contenido">
                <?= nl2br(htmlspecialchars($entrada['descripcion'])) ?>
            </div>
        </article>
    </main>
</body>
</html>
