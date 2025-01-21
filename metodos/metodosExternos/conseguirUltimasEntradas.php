<?php

function conseguirUltimasEntradas($conexion, $limit = 5) {
    try {
        // Consulta con INNER JOIN para obtener las últimas entradas y sus categorías
        $sql = "SELECT e.id AS id_entrada, e.titulo, e.descripcion, e.fecha, 
                       c.nombre AS categoria 
                FROM entradas e
                INNER JOIN categorias c ON e.categoria_id = c.id
                ORDER BY e.id DESC
                LIMIT :limit";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Devuelve las entradas como un array asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener las últimas entradas: " . $e->getMessage());
        return [];
    }
}
