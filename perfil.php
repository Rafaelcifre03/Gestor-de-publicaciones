<?php

/*include_once("./mySqlServer/conexion.php");*/
include_once("./libs/pdo.php");

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
    <title>Perfil</title>
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
            /* display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            justify-content: center;
            gap: 10px; */
        }

        main>img {
            margin: 0 auto;
        }

        main>div#content {
            display: flex;
            flex-direction: column;
            /* 
            align-content: center;
            justify-content: center; */
        }

        div#content>div#user {
            display: flex;
            align-content: center;
            justify-content: center;
            padding-bottom: 30px;
            border-bottom: 1px solid #000;
        }

        div#userimages {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            text-align: center;
        }

        div#userimages>div {
            display: flex;
            flex-direction: column;
            margin: 10px;
        }

        p {
            text-align: center;
        }

        div.hdr {
            display: flex;
        }

        div.hdr>div {
            margin: 0 30px;
        }

        #fotoPerfil {
            border: black 4px solid;
            text-align: center;
            padding: 10px;
        }

        #perfil {
            display: flex;
            flex-wrap: nowrap;
            text-align: center;

            padding: 10%;
        }

        img {
            height: 250px;
            width: 250px;
        }

        h2 {
            border-bottom: black 2px solid;
            margin-bottom: 10px;
        }

        #datos {
            text-align: left;
            margin-left: 30px;
            padding-top: 20px;
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

        <div id="content">
            <div id="perfil">
                <div id="fotoPerfil">
                <h2>Foto Perfil</h2>
                    <?php

                    if (file_exists ($_SESSION['usuario']."/".$_SESSION['usuario'].".png") || file_exists ($_SESSION['usuario']."/".$_SESSION['usuario'].".jpg") || file_exists ($_SESSION['usuario']."/".$_SESSION['usuario'].".jpeg"))
                    {
                        /* ! codigo sin Clase PDO !
                        $directorio = $_SESSION['usuario'] . "/";

                        $consulta = "select fotoPerfil FROM usuario WHERE id=?";

                        try {
                            $resultado = $pdo->prepare($consulta);
                            $resultado->bindParam(1, $_SESSION['id']);
                            if ($resultado->execute()) {
                                $arrayResultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($arrayResultado as $row) {
                                    echo "<div> <img src=" . $directorio . $row['fotoPerfil'] . "> </div>";
                                }
                            }
                        } catch (PDOException $e) {
    
                            // En este caso guardamos los errores en un archivo de errores log
                            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                            // guardamos en ·errores el error que queremos mostrar a los usuarios
                            $errores['datos'] = "Ha habido un error <br>";
                        }
                        */

                        try {
                            $usuario = new Usuario();

                            $datosUsuario = $usuario->get_Usuario($_SESSION['id']);

                            echo "<div> <img src=" . $datosUsuario[0]['usuario']. "/" . $datosUsuario[0]['fotoPerfil'] . "> </div>";

                        } catch (PDOException $e) {
        
                            // En este caso guardamos los errores en un archivo de errores log
                            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                            // guardamos en ·errores el error que queremos mostrar a los usuarios
                            $errores['datos'] = "Ha habido un error <br>";
                    }
                    }
                    else
                        echo "<div> <img src=" . "fotoPerfil/500x500.png> </div>";
                    ?>
                </div>
                <div id="datos">
                    <p>Nombre: <?php echo $datosUsuario[0]['usuario']?></p>
                </div>
            </div>


            <div id="userimages">

                <?php

                /*  ! codigo sin Clase PDO !
                //mostrar datos
                $consulta = "select nombre, comentario, privada FROM imagenes WHERE idUsuario=?";

                try {
                    $resultado = $pdo->prepare($consulta);
                    $resultado->bindParam(1, $_SESSION['id']);
                    if ($resultado->execute()) {
                        $arrayResultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($arrayResultado as $row) {
                            if ($row['privada'] == 1)
                                echo "<div> <img src=" . $directorio . $row['nombre'] . "> <p> ~Foto privada~<br>" . $row['comentario'] . "</p> </div>";
                            else
                                echo "<div> <img src=" . $directorio . $row['nombre'] . "> <p> ~Foto publica~<br>" . $row['comentario'] . "</p> </div>";
                        }
                    }
                } catch (PDOException $e) {

                    // En este caso guardamos los errores en un archivo de errores log
                    error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                    // guardamos en ·errores el error que queremos mostrar a los usuarios
                    $errores['datos'] = "Ha habido un error <br>";
                }
                */

                try {

                    $imagen = new Imagen();

                    $datosImagenes = $imagen->get_Imagenes($_SESSION['id']);

                    foreach ($datosImagenes as $row)
                    {
                        echo "<div> <img src=" . $datosUsuario[0]['usuario']. "/" . $row['nombre'] . "> </div>";
                    }
                } catch (PDOException $e) {

                    // En este caso guardamos los errores en un archivo de errores log
                    error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                    // guardamos en ·errores el error que queremos mostrar a los usuarios
                    $errores['datos'] = "Ha habido un error <br>";
                }



                /*  MUESTRA LAS IMAGENES Y COMENTARIOS Pillandolos de la carpeta
                
                    $directorio = $_SESSION['usuario']."/";

                    $arrayImagenes = array();
                    $arrayComentarios = array();
                    if (is_dir($directorio))
                    {
                        $arrayDir = scandir($directorio, 0);
                    
                        foreach ($arrayDir as $key => $value)
                        {  
                            $extencion_archivo = pathinfo($value)["extension"];
                            if ($extencion_archivo != "txt" && $value != "." && $value != "..")
                                $arrayImagenes[] = $value;
                            else if ($value != "." && $value != "..")
                                $arrayComentarios[] = $value;
                        }

                        foreach ($arrayImagenes as $key => $value)
                        {
                            $imagen = substr($value, 0, -mb_strlen($extencion_archivo)-1);

                            foreach ($arrayComentarios as $key2 => $value2)
                            {  
                                $Nombcomentario = substr($value2, 0, -mb_strlen($extencion_archivo)-1);

                                if ($imagen == $Nombcomentario)
                                {
                                    $contenido = file_get_contents($directorio.$value2);

                                    echo "<div> <img src=".$directorio.$value."> <p>$contenido</p> </div>";
                                }
                            }
                        }
                    }
                */
                ?>

            </div>
        </div>
    </main>
</body>

</html>