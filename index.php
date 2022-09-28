<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: login.php");
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

        header {
            display: flex;
            justify-content: space-around;
            background-color: #aaa;
        }

        main {
            padding: 50px 10%;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        main>img {
            margin: 0 auto;
        }

        div.hdr {
            display: flex;
        }

        div.hdr>div {
            margin: 0 30px;
        }

        h1 {
            border-bottom: black 4px dashed;
            text-align: center;
            grid-row: 1/1;
            grid-column: 1/4;
        }

        img {
            height: 250px;
            width: 250px;
        }
    </style>
</head>

<body>
    <header>
        <div class="hdr">
            <div>
                <a href="index.php">Inicio</a>
            </div>
            <div>
                <a href="subir.php">Subir imagen</a>
            </div>
        </div>

        <div class="hdr">
            <div>
                <a href="perfil.php">Mi perfil</a>
            </div>
            <div>
                <a href="logout.php">Salir</a>
            </div>
        </div>


    </header>
    <main>
        <h1>Publicaciones</h1>
        
        <?php
        /* ! codigo sin CLASE PDO !
        require_once("./mySqlServer/conexion.php");
        $privacidad = "0";

        $consulta = "select imagenes.nombre, imagenes.comentario, usuario.usuario, imagenes.privada FROM usuarios.imagenes inner join usuario on imagenes.idUsuario = usuario.id WHERE privada=?";

        try {
            $resultado = $pdo->prepare($consulta);
            $resultado->bindParam(1, $privacidad);
            if ($resultado->execute()) {
                $arrayResultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
                foreach ($arrayResultado as $row) {
                    
                    $directorio = $row['usuario']."/";
                    echo "<div> <img src=" . $directorio . htmlentities($row['nombre'])  . "> <p>" . $row['comentario'] . "</p> </div>";
                }
            }
        } catch (PDOException $e) {

            // En este caso guardamos los errores en un archivo de errores log
            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
            // guardamos en ·errores el error que queremos mostrar a los usuarios
            $errores['datos'] = "Ha habido un error <br>";
        }
        */ 
        include_once("./libs/pdo.php");

        try {
            $imagen = new Imagen();

            $datosImagenes = $imagen->get_ImagenesPrivada(false);

            foreach ($datosImagenes as $row)
            {
                echo "<div> <img src=" . $row['usuario'] . "/". $row['nombre'] . "></div>";
            }

        } catch (PDOException $e) {

            // En este caso guardamos los errores en un archivo de errores log
            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
            // guardamos en ·errores el error que queremos mostrar a los usuarios
            $errores['datos'] = "Ha habido un error <br>";
        }

        

        ?>
    </main>
</body>

</html>