<?php
session_start();
require_once '../requires/conexion.php';
require_once './metodosExternos/conseguirCategorias.php';

// Validar que el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// Obtener el ID de la entrada
$entrada_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Cargar los datos de la entrada
$sql = "SELECT * FROM entradas WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $entrada_id, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $_SESSION['usuario']['id'], PDO::PARAM_INT);
$stmt->execute();
$entrada = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entrada) {
    die("Error: Entrada no encontrada o no tienes permisos para editarla.");
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $categoria_id = (int)$_POST['categoria_id'];

    if ($titulo && $descripcion && $categoria_id > 0) {
        $sql = "UPDATE entradas 
                SET titulo = :titulo, descripcion = :descripcion, categoria_id = :categoria_id
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':id', $entrada_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $_SESSION['usuario']['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            // header("Location: verEntrada.php?id=$entrada_id");
            header("Location: ./listarTodasEntradas.php");
            exit;
        } else {
            $error = "Error al actualizar la entrada.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrada</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>


<main class="editar-entrada-main">
    <form class="editar-entrada-form" action="" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($entrada['titulo']) ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($entrada['descripcion']) ?></textarea>

        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id" required>
            <?php
            $categorias = conseguirCategorias($db);
            foreach ($categorias as $categoria):
            ?>
                <option value="<?= $categoria['id'] ?>" <?= $categoria['id'] == $entrada['categoria_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Guardar Cambios</button>
    </form>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</main>
</body>
</html>
