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
    // Comprobamos que la contraeña es válida
    $password = trim($_POST['passwordLogin']);
    $recuerdame = isset($_POST['recuerdame']); // Checkbox "Recuérdame"

    if ($email && $password) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        if ($stmt->rowCount() == 1) { //si la contraseña es correcta
            $usuario = $stmt->fetch();
            // die(var_dump($usuario));
            if ($usuario['email'] === $email) {
                if (password_verify($password, $usuario['password'])) {
                    // die(var_dump($usuario["nombre"]));
                    $_SESSION['usuario'] = [
                        'id' => $usuario['id'],
                        'nombre' => $usuario['nombre'],
                        'apellidos' => $usuario['apellidos'],
                        'email' => $usuario['email']
                    ];

                    $_SESSION['errorInicioSesion'] = 0;
                    $_SESSION['loginExito'] = true;

                //Guardo en el email en una cookie si el ckeckbox está activado
                if ($recuerdame) {
                    setcookie("emailLogin", $email, time() + (30 * 24 * 60 * 60), "/"); //expira en 30 días
                } else {
                    //Si el usuario no marca "Recuérdame" se eliminar la cookie
                    setcookie("emailLogin", "", time() - 3600, "/");
                }

                header("Location: index.php");
                exit();

                } else {
                    $_SESSION['errorPassLogin'] = "La contraseña no es correcta.";
                    $_SESSION['errorInicioSesion']++;
                    $_SESSION['ultimoIntento'] = time();
                    $_SESSION['errorEmailLogin'] = null;
                }
            } else {
                $_SESSION['errorEmailLogin'] = "El email no es correcto.";
                $_SESSION['errorInicioSesion']++;
                $_SESSION['ultimoIntento'] = time();
            }
        } else {
            // Si no se encuentra el email en la base de datos
            $_SESSION['errorEmailLogin'] = "El email no se encuentra registrado.";
            $_SESSION['errorInicioSesion']++;
            $_SESSION['ultimoIntento'] = time();
        }
    } else {
        echo "El email o contraseña errónea";
    }
    header("Location: index.php");
    exit();
}


// 7. Controlamos los 3 intentos fallidos de inicio de sesión
echo "Error inicio sesion" . var_dump($_SESSION['errorInicioSesion']);
echo "ultimo intento: " . var_dump($_SESSION['ultimoIntento']);
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
