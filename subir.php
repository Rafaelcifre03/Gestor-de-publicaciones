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
    <title>Subir imagen</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        header {
            display: flex;
            justify-content: space-around;
            background-color: #aaa;
        }
        div.hdr {
            display: flex;
        }

        div.hdr>div {
            margin: 0 30px;
        }

        .container {
            display: flex;
            align-content: space-around;
            text-align: center;
            padding: 200px;
        }
        div {
            text-align: center;
            flex: 1;
        }
        img {
            flex: 1 1 auto;
            height: 250px;
            max-width: 250px; 
            
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
        <?php 
            require_once("bGeneral.php");
            require_once("./mySqlServer/conexion.php");

            $extensiones = ["jpg", "jpeg", "png"];
            $errores = array();

            if (isset($_REQUEST["boton"]))
            {
                $checkPrivacidad= recogeCheck("privada");
                $comentario = recogeTodo("comentario");

                esValidoTexto($comentario, $errores, "comentario");

                cFileComprobar("imagen", "imagenUser", $errores, $extensiones, 51200000);

                /* ! codigo sin CLASE PDO !
                if (count($errores)==0)
                {
                    //guardo imagen
                    if (is_dir($_SESSION["usuario"]."/"))
                    {
                        cPrivacidadImagen($checkPrivacidad, $comentario, $pdo);
                    }
                    else
                    {
                        mkdir($_SESSION["usuario"]."/",0777,true);

                        cPrivacidadImagen($checkPrivacidad, $comentario, $pdo);
                    }
                    header("location: perfil.php");
                }
                */
                if (count($errores)==0)
                {
                    if (is_dir($_SESSION["usuario"]."/"))
                    {
                        try {

                            $imagen->set_Imagenes($_SESSION['id'], $_SESSION['usuario'], $checkPrivacidad, $comentario);

                        }catch (PDOException $e) /*  */{ 
                            // En este caso guardamos los errores en un archivo de errores log
                            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                            // guardamos en ·errores el error que queremos mostrar a los usuarios
                            $errores['datos'] = "Ha habido un error <br>";
                        }
                    }
                    else
                    {
                        mkdir($_SESSION["usuario"]."/",0777,true);

                        try {

                            $imagen->set_Imagenes($_SESSION['id'], $_SESSION['usuario'], $checkPrivacidad, $comentario);
                            
                        }catch (PDOException $e) /*  */{ 
                            // En este caso guardamos los errores en un archivo de errores log
                            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
                            // guardamos en ·errores el error que queremos mostrar a los usuarios
                            $errores['datos'] = "Ha habido un error <br>";
                        }
                    }
                    header("location: perfil.php");
                }
                else
                {
                    foreach ($errores as $key => $value)
                    {
                        echo $value ."<br>";
                    }
                    ?>
                    <div class="container">
                        <img href="#">
                        <div>
                            <h1>Subir publicacion</h1>
                            <form action="" method="POST" enctype="multipart/form-data">
                                Imagen: <input type="file" name="imagen"><br><br>
                                Comentario: <textarea name="comentario" placeholder="comentario de la imagen"></textarea><br><br>
                                establecer imagen privada &nbsp; <input type="checkbox" name="privada"><br><br>
                                <input type="submit" name="boton">
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            else
            {?>
                <div class="container">
                    <img href="#">
                    <div>
                        <h1>Subir publicacion</h1>
                        <form action="" method="POST" enctype="multipart/form-data">
                            Imagen: <input type="file" name="imagen"><br><br>
                            Comentario: <br><textarea name="comentario" placeholder="comentario de la imagen"></textarea><br><br>
                            establecer imagen privada &nbsp; <input type="checkbox" name="privada"><br><br>
                            <input type="submit" name="boton">
                        </form>
                    </div>
                </div>
                <?php
            }
        ?>



    
    
</body>
</html>