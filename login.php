<?php
session_start();

require_once 'requires/conexion.php';

// 7. Definimos una variable de sesión para controlar los 3 intentos fallidos de inicio de sesión
$_SESSION['errorInicioSesion'] = $_SESSION['errorInicioSesion'] ?? 0;
$_SESSION['ultimoIntento'] = $_SESSION['ultimoIntento'] ?? time();
$_SESSION['loginExito'] = $_SESSION['loginExito'] ?? false;

// 6. Formulario de Inicio de Sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['botonLogin']) && $_SESSION['errorInicioSesion'] < 3) {
    // Comprobamos que el email es válido
    $email = filter_var(trim($_POST['emailLogin']), FILTER_VALIDATE_EMAIL);
    // Comprobamos que la contraseña es válida
    $password = trim($_POST['passwordLogin']);

    if ($email && $password) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);//Se utiliza por seguridad
        $stmt->execute();

        if ($stmt->rowCount() == 1) {//si la contraseña es correct
            $user = $stmt->fetch(); 
            // die(var_dump($usuario));
            if (password_verify($password, $user['password'])) {
                $_SESSION['errorInicioSesion'] = 0; 
                $_SESSION['loginExito'] = true; 
                $_SESSION['usuario_id'] = $user['id']; // Guardar el id del usuario para poder manejarlo

                header("Location: index.php");
                exit();
            } else {
                $_SESSION['errorPassLogin'] = "La contraseña no es correcta.";
                $_SESSION['errorInicioSesion']++;
                $_SESSION['ultimoIntento'] = time(); 
            }
        } else {
            $_SESSION['errorPassLogin'] = "El email no existe en nuestra Base de Datos.";
        }
    } else {
        $_SESSION['errorPassLogin'] = "Por favor, introduce un email y contraseña válidos.";
    }

    header("Location: login.php");
    exit();
}

// 7. Controlamos los 3 intentos fallidos de inicio de sesión
if ($_SESSION['errorInicioSesion'] >= 3) {
    $tiempoRestante = time() - $_SESSION['ultimoIntento'];
    if ($tiempoRestante < 5) {
        // Bloqueo al usuario durante 5 segundos
        echo "<script> 
            setTimeout(function() {
                window.location.reload();
            }, 5000);
        </script>";
    } else {
        // Hacemos un reset de los errores si han pasado más de 5 segundos
        $_SESSION['errorInicioSesion'] = 0;
    }
}
?>
