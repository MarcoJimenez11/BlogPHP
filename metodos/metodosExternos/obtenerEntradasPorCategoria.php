<?php
function obtenerEntradasPorCategoria($conexion, $categoria_id) {
    try {
        $sql = "SELECT e.id, e.titulo, e.descripcion, c.nombre AS categoria, 
                       u.nombre AS autor, e.fecha 
                FROM entradas e
                INNER JOIN categorias c ON e.categoria_id = c.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                WHERE e.categoria_id = :categoria_id
                ORDER BY e.fecha DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener las entradas de la categoría: " . $e->getMessage());
    }
}
?>
