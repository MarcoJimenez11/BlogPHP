<?php
session_start();
require_once '../requires/conexion.php';
require_once './metodosExternos/conseguirCategorias.php';
require_once "../requires/header.php";

//Validar que el usuario esta identificado
if (!isset($_SESSION['loginExito']) || $_SESSION['loginExito'] === false || !isset($_SESSION['usuario']['id'])) {
    header("Location: index.php");
    exit;
}
//Obtener el id del usuario
$usuario_id = $_SESSION['usuario']['id'];

// Verificar el usuario
if (!$usuario_id || !is_numeric($usuario_id)) {
    header("Location: index.php");
    exit;
}

$categorias = conseguirCategorias($db);

// Inicializar variables para evitar errores la primera vez que se entra
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoria_id = $_POST['categoria'] ?? '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($titulo);
    $descripcion = trim($descripcion);
    $categoria_id = (int)$categoria_id;
    $fecha = date('Y-m-d');

    // Validar 
    if (empty($titulo)) {
        $errores[] = "El título es obligatorio.";
    }
    if (empty($descripcion)) {
        $errores[] = "La descripción es obligatoria.";
    }
    if ($categoria_id <= 0) {
        $errores[] = "Selecciona una categoría válida.";
    }

    // Si no hay errore insertar la entrada en la base de datos
    if (empty($errores)) {
        try {
            $query = $db->prepare(
                "INSERT INTO entradas (titulo, descripcion, categoria_id, usuario_id, fecha) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $query->bindParam(1, $titulo, PDO::PARAM_STR);
            $query->bindParam(2, $descripcion, PDO::PARAM_STR);
            $query->bindParam(3, $categoria_id, PDO::PARAM_INT);
            $query->bindParam(4, $usuario_id, PDO::PARAM_INT);
            $query->bindParam(5, $fecha, PDO::PARAM_STR);
            $query->execute();

            // Redirigir al inicio
            header("Location: ../index.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al guardar la entrada: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Entrada</title>
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body>
    <header>
        <h1>Crear Nueva Entrada</h1>
    </header>
    <main>
        <section class="formulario">
            <?php if (!empty($errores)): ?>
                <div class="errores">
                    <?php foreach ($errores as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($titulo) ?>" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($descripcion) ?></textarea>

                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria" required>
                    <option value="">-- Selecciona una categoría --</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= ($categoria_id == $categoria['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Guardar Entrada</button>
                <a href="../index.php">
                    <button type="button">Volver al Inicio</button>
                </a>
            </form>
        </section>
    </main>
</body>
</html>
