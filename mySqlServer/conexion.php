<?php

//Datos de configuración a la BD. Posteriormente los sacaremos a un fichero de configuración config.php

$db_hostname = "localhost";
$db_nombre = "usuarios";

/*
 * El usuario root núnca se puede usar, siempre cambiar por otro usuario
 * Nosotros lo usaremos para que nos funcionen a todos los ejemplos y los ejercicios
 */
$db_usuario = "root";
$db_clave = "";

//Ponemos la conexión dentro de un bloque try para poder capturar la excepción, si la hubiera
 
try {
    // Conectamos
    $pdo = new PDO('mysql:host=' . $db_hostname . ';dbname=' . $db_nombre . '', $db_usuario, $db_clave);
    // Realiza el enlace con la BD en utf-8
    $pdo->exec("set names utf8");
    //Accionamos el uso de excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En este caso guardamos los errores en un archivo de errores log
    error_log($e->getMessage() . "## Fichero: " . $e->getFile() . "## Línea: " . $e->getLine() . "##Código: " . $e->getCode() . "##Instante: " . microtime() . PHP_EOL, 3, "logBD.txt");
    //guardamos en ·errores el error que queremos mostrar a los usuarios
    $errores['datos'] = "Ha habido un error <br>";
}


// Si todo va bien en $db tendremos el objeto que gestionará la conexión con la base de datos.
