<?php
if (isset($_POST['login'])) {
    require("bGeneral.php");
    require("./mySqlServer/conexion.php");

    $errores = array();
    if (cNombreUsuario(recoge("usuario", FALSE, "usuario", $errores), "usuario2", $errores)) {
        $usuario = recoge("usuario");
    }

    if (cContrasena(recoge("pw1"), "pw", $errores)) {
        $pw = recoge("pw1");
    }

    $mantener = recogeCheck("mantener");

    if (count($errores) === 0) {
        if (!cUsuarioExisteBD($usuario, $errores, "usuario", $pdo, 1)) {
            $consulta = "SELECT * FROM usuario where usuario='$usuario'";
            $result = $pdo->query($consulta)->fetch();
            if (password_verify($pw, $result['contrasena'])) {
                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['usuario'] = $usuario;
                header('location: index.php');
            } else {
                $errores["pw"] = "Contraseña no válida";
            }
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
    <title>Login</title>
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
            <form method="POST">
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
                if (isset($errores['pw'])) {
                    echo "<p style=\"color: red;\">" . $errores['pw'] . "</p>";
                }
                ?>
                <label for="pw1">contraseña</label>
                <input name="pw1" type="password" placeholder="contraseña">
                <br>
                <input name="mantener" type="checkbox">
                <label for="mantener">mantener la sesión iniciada</label>
                <br>
                <button name="login">Login</button>
            </form>
        </div>
    </main>
</body>

</html>