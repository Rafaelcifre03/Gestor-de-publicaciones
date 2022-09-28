<?php
if (isset($_POST['register'])) {
    require("bGeneral.php");
    require("./mySqlServer/conexion.php");

    $errores = array();

    $extValidas = array(
        "jpg",
        "jpeg",
        "png"
    );

    if (cNombreUsuario(recoge("usuario", FALSE, "usuario", $errores), "usuario2", $errores)) {
        $usuario = recoge("usuario");
        cUsuarioExisteBD($usuario, $errores, "usuario", $pdo, 2);
    }

    if (cEmail(recoge("email", FALSE, "email", $errores), "email2", $errores)) {
        $email = recoge("email");
    }

    if (cContrasenas(recoge("pw1"), recoge("pw2"), "pw", $errores)) {
        $pw = recoge("pw1");
    }

    $fotovalida = cFileComprobar("perfil", "perfil", $errores, $extValidas, 16777216, false);

    if (count($errores) === 0) {

        if (!is_dir($usuario . "/")) {
            mkdir($usuario . "/", 0777, true);
        } 

        if ($fotovalida) {
            $nombrefoto = cFileMover("perfil", "perfilm", $errores, $usuario . "/", $usuario);
        } else {
            $nombrefoto = "default.png";
        }

        if (count($errores) === 0) {

            $usuarios = [
                'usuario' => $usuario,
                'contrasena' => password_hash($pw, PASSWORD_DEFAULT),
                'fAlta' => date('y-m-d'),
                'email' => $email,
                'fotoPerfil' => $nombrefoto,
                'activo' => true
            ];
            try {
                // Preparamos la consulta
                $stmt = $pdo->prepare("INSERT INTO usuario (usuario, contrasena, fAlta, email, fotoPerfil, activo) values (:usuario, :contrasena, :fAlta, :email, :fotoPerfil, :activo)");

                // Vinculamos al ejecutar utilizando el array asociativo

                $stmt->execute($usuarios);
                #if ($stmt->execute($usuarios))
                #    echo "El id del último usuario dado de alta es: " . $pdo->lastInsertId();
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id'] = $pdo->lastInsertId();
            } catch (PDOException $e) {
                // En este caso guardamos los errores en un archivo de errores log
                error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                // guardamos en ·errores el error que queremos mostrar a los usuarios
                $errores['datos'] = "Ha habido un error <br>";
            }

            $db = NULL;

            /* if (!$fotovalida) {
                $_SESSION['defaultpfp'] = true;
            } */
            header('location: index.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #ddd;
        }

        div.hdr {
            display: flex;
        }

        div.hdr>div {
            margin: 0 30px;
        }

        header {
            display: flex;
            justify-content: space-around;
            background-color: #aaa;
        }

        main {
            padding: 100px 15%;
            display: flex;
            align-items: center;
        }

        main>div {
            margin: 0 10%;
        }
    </style>
</head>

<body>
    <header>
        <div class="hdr">
            <div>
                <a href="index.php">Inicio</a>
            </div>
        </div>

        <div class="hdr">
            <div>
                <a href="login.php">Login</a>
            </div>
            <div>
                <a href="register.php">Register</a>
            </div>
        </div>
    </header>
    <main>
        <div>
            <img src="500x500.png" alt="">
        </div>
        <div>
            <form method="POST" enctype="multipart/form-data">

                <?php
                if (isset($errores['usuario'])) {
                    echo "<p style=\"color: red;\">" . $errores['usuario'] . "</p>";
                }
                if (isset($errores['usuario2'])) {
                    echo "<p style=\"color: red;\">" . $errores['usuario2'] . "</p>";
                }
                ?>

                <label for="usuario">usuario</label>
                <input name="usuario" type="text" placeholder="usuario">
                <br>

                <?php
                if (isset($errores['email'])) {
                    echo "<p style=\"color: red;\">" . $errores['email'] . "</p>";
                }
                if (isset($errores['email2'])) {
                    echo "<p style=\"color: red;\">" . $errores['email2'] . "</p>";
                }
                ?>
                <label for="email">email</label>
                <input name="email" type="email" placeholder="usuario">
                <br>
                <?php
                if (isset($errores['pw'])) {
                    echo "<p style=\"color: red;\">" . $errores['pw'] . "</p>";
                }
                ?>
                <label for="pw1">contraseña</label>
                <input name="pw1" type="password" placeholder="contraseña">
                <br>
                <label for="pw2">repite la constaseña</label>
                <input name="pw2" type="password" placeholder="contraseña">
                <br>
                <?php
                if (isset($errores['perfil'])) {
                    echo "<p style=\"color: red;\">" . $errores['perfil'] . "</p>";
                }
                if (isset($errores['perfilm'])) {
                    echo "<p style=\"color: red;\">" . $errores['perfilm'] . "</p>";
                }
                ?>
                <label for="perfil">Foto de perfil</label>
                <input name="perfil" type="file">
                <br>
                <button name="register">Registro</button>
            </form>
        </div>
    </main>
</body>

</html>