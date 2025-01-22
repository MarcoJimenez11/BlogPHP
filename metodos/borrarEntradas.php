<?php

// Creamos la sesión
session_start();

// Importamos la conexion a la BDD
require_once '../requires/conexion.php';

// Validamos la sesión del usuario
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Obtenemos el ID de cada entrada para poder identificarla.
$idEntrada = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

// Verificamos que el ID de la entrada y el ID del usuario sean válidos
if ($idEntrada <= 0 || !isset($_SESSION["usuario"]["id"])) {
    die("ID de entrada o ID de usuario no válido");
}

// Hacemos la consulta para borrar la entrada seleccionada
$sql = "DELETE FROM entradas WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $idEntrada, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $_SESSION["usuario"]["id"], PDO::PARAM_INT);

// Ahora hacemos una comprobación, si la consulta se ejecuta y afecta alguna fila.
if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        header("Location: ./listarTodasEntradas.php");
        exit;
    } else {
        die("No se encontró la entrada o no tienes permiso para borrarla");
    }
} else {
    $errorInfo = $stmt->errorInfo();
    die("Error al ejecutar la consulta: " . $errorInfo[2]);
}
?>