<?php

function cabecera(string $titulo = "Ejemplo") // el archivo actual
{
?>
	<!DOCTYPE html>
	<html lang="es">

	<head>
		<title>
			<?php
			echo $titulo;
			?>

		</title>
		<meta charset="utf-8" />
	</head>

	<body>
	<?php
}

function pie()
{
	echo "</body>
	</html>";
}

function sinTildes(string $frase)
{
	$no_permitidas = array(
		"á",
		"é",
		"í",
		"ó",
		"ú",
		"Á",
		"É",
		"Í",
		"Ó",
		"Ú",
		"à",
		"è",
		"ì",
		"ò",
		"ù",
		"À",
		"È",
		"Ì",
		"Ò",
		"Ù"
	);
	$permitidas = array(
		"a",
		"e",
		"i",
		"o",
		"u",
		"A",
		"E",
		"I",
		"O",
		"U",
		"a",
		"e",
		"i",
		"o",
		"u",
		"A",
		"E",
		"I",
		"O",
		"U"
	);
	$texto = str_replace($no_permitidas, $permitidas, $frase);
	return $texto;
}

/* Permite quitar todos los espacios si le pasamos como segundo parámetro la cadena vacía */
function sinEspacios(string $frase, string $espacio = " ")
{
	$texto = trim(preg_replace('/ +/', $espacio, $frase));
	return $texto;
}

// Si el campo es opcional, no hace falta $campo ni $errores. Si una de las dos variables está la otra también debe.
function recoge(string $var, bool $espacios = FALSE, $campo = NULL, &$errores = NULL)
{
	$espacios = $espacios ? "" : " ";
	if (isset($_REQUEST[$var]))
		$tmp = strip_tags(sinEspacios($_REQUEST[$var], $espacios));
	else {
		$errores[$campo] = "Este campo es requerido.";
		$tmp = "";
	}
	return $tmp;
}

function recogeTodo($var)
{
	if (isset($_REQUEST[$var]))
		$temp = htmlspecialchars(nl2br($_REQUEST[$var]));
	else
		$temp = "";

	return $temp;
}

function esValidoTexto(String $texto, &$errores, $campo = "textArea")
{
	if (!empty($texto))
		return true;

	else {
		$errores[$campo] = "El $campo esta vacio";
		return false;
	}
}

function recogeCheck(string $text)
{
	if (isset($_REQUEST[$text]))
		return true;
	else
		return false;
}

// --
function recogeRadio(string $text, $campo = NULL, &$errores = NULL)
{
	if (isset($_REQUEST[$text]))
		return strip_tags(sinEspacios($_REQUEST[$text]));
	else {
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Marca una opción.";
		}
		return false;
	}
}

// Si se le pasan campo y errores, es que el campo es obigatorio.
/* Cadenas numéricas por defecto entre 1 y 10 dígitos */
function cNum(string $num, int $max = 10, int $min = 1, string $campo = NULL, array &$errores = NULL)
{
	if ((preg_match("/^[0-9]{" . $min . "," . $max . "}$/", $num))) {
		return true;
	}

	if (!is_null($campo) && !is_null($errores)) {
		$errores[$campo] = "El $campo solo puede contener números de $min a $max dígitos";
	}
	return false;
}

/* Por defecto es no sensible a mayúsculas, permite un espacio entre palabras y cadenas de longitud entre 1 y 30 */
function cTexto(string $text, string $campo, array &$errores, int $max = 30, int $min = 1, string $espacios = " ", string $case = "i")
{
	if ((preg_match("/^[A-Za-zÑñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {

		return true;
	}
	$errores[$campo] = "El $campo sólo puede contener letras";
	return false;
}

// Le pasamos nombre del campo, ruta definitiva, extensiones válidas y array errores
// Primero comprobamos que el archivo sea válido.
function cFileComprobar($nombrecampo, $campo, &$errores, &$extensionesValidas, $size, $guardarerrores = true)
{
	if ($_FILES[$nombrecampo]['error'] != 0) {
		if ($guardarerrores) {
			$errores[$campo] = "Ha habido un problema al subir tu fichero. Vuelve a intentarlo";
		}
		return false;
	} else {
		// Guardamos el nombre original del fichero
		$nombreArchivo = $_FILES[$nombrecampo]['name'];
		// Guardamos nombre del fichero en el servidor
		$directorioTemp = $_FILES[$nombrecampo]['tmp_name'];

		// Comprobamos extensión
		$arrayArchivo = pathinfo($nombreArchivo);
		/*
         * Extraemos la extensión del fichero, desde el último punto. Si hubiese doble extensión, no lo
         * tendría en cuenta.
         */
		$extension = $arrayArchivo['extension'];
		// Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
		if (!in_array($extension, $extensionesValidas)) {
			if ($guardarerrores) {
				$errores[$campo] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
			}

			return false;
		}

		// Comprobamos el tamaño del archivo
		if (filesize($directorioTemp) > $size) {
			if ($guardarerrores) {
				$errores[$campo] = "La imagen debe de tener un tamaño inferior a $size M y no " . filesize($directorioTemp);
			}

			return false;
		}
	}
	return true;
}

// Después de comprobar y que todo esté bien, se mueve el archivo.
function cFileMover($nombrecampo, $campo, &$errores, $ruta, $nombrePersonal = NULL)
{
	$nombreArchivo = $_FILES[$nombrecampo]['name'];
	// Guardamos nombre del fichero en el servidor
	$directorioTemp = $_FILES[$nombrecampo]['tmp_name'];

	// Comprobamos extensión
	$arrayArchivo = pathinfo($nombreArchivo);
	/*
         * Extraemos la extensión del fichero, desde el último punto. Si hubiese doble extensión, no lo
         * tendría en cuenta.
         */
	$extension = $arrayArchivo['extension'];
	// Almacenamos el archivo en ubicación definitiva
	// Añadimo time() al nombre del fichero, así lo haremos único y si tuviera doble extensión

	// El nombre del archivo dependerá si le hemos pasado o no un nombre
	if (is_null($nombrePersonal))
		$nombreArchivo = time() . $nombreArchivo;
	else
		$nombreArchivo = $nombrePersonal . "." . $extension;

	// Movemos el fichero a la ubicación definitiva
	// @ para suprimir los errores.
	// Es para que simplemente salga el error del else en caso de que la carpeta no exista, o no tenga permisos.
	if (@move_uploaded_file($directorioTemp, $ruta . $nombreArchivo)) {
		// En este caso devolvemos sólo el nombre del fichero sin la ruta
		return $nombreArchivo;
	} else {
		$errores[$campo] = "Error: No se puede mover el fichero a su destino";
		return false;
	}
}

// No la uso, la he partido en dos más arriba
function cFile($nombrecampo, $campo, &$errores, $ruta, &$extensionesValidas, $size, $nombrePersonal = NULL)
{
	if ($_FILES[$nombrecampo]['error'] != 0) {
		$errores[$campo] = "Ha habido un problema al subir tu fichero. Vuelve a intentarlo";
		return false;
	} else {
		// Guardamos el nombre original del fichero
		$nombreArchivo = $_FILES[$nombrecampo]['name'];
		// Guardamos nombre del fichero en el servidor
		$directorioTemp = $_FILES[$nombrecampo]['tmp_name'];

		// Comprobamos extensión
		$arrayArchivo = pathinfo($nombreArchivo);
		/*
         * Extraemos la extensión del fichero, desde el último punto. Si hubiese doble extensión, no lo
         * tendría en cuenta.
         */
		$extension = $arrayArchivo['extension'];
		// Comprobamos la extensión del archivo dentro de la lista que hemos definido al principio
		if (!in_array($extension, $extensionesValidas)) {
			$errores[$campo] = "La extensión del archivo no es válida o no se ha subido ningún archivo";
			return false;
		}

		// Comprobamos el tamaño del archivo
		if (filesize($directorioTemp) > $size) {
			$errores[$campo] = "La imagen debe de tener un tamaño inferior a $size M y no " . filesize($directorioTemp);
			return false;
		}

		// Almacenamos el archivo en ubicación definitiva
		// Añadimo time() al nombre del fichero, así lo haremos único y si tuviera doble extensión

		// El nombre del archivo dependerá si le hemos pasado o no un nombre
		if (is_null($nombrePersonal))
			$nombreArchivo = time() . $nombreArchivo;
		else
			$nombreArchivo = $nombrePersonal . "." . $extension;

		// Movemos el fichero a la ubicación definitiva
		if (move_uploaded_file($directorioTemp, $ruta . $nombreArchivo)) {
			// En este caso devolvemos sólo el nombre del fichero sin la ruta
			return $nombreArchivo;
		} else {
			$errores[$campo] = "Error: No se puede mover el fichero a su destino";
			return false;
		}
	}
}


// Comprobar IBAN
// [ES|FR|DE]
// [ ]{0,1} -> Espacio opcional (en caso de no usar la función sinEspacios)
// [0-9]{4} -> 4 números
// Ejemplos
// ES4101004000511234564321 -> 22 números
// errores y campo en caso de validar campo obligatorio

function cIBAN($str, $errores = NULL, $campo = NULL)
{
	$str = sinEspacios($str, "");
	return preg_match("/^(ES|FR|DE)[0-9]{22}$/i", $str);
}

// Crea select automáticamente
function pintaSelect(string $nombre, array $valores)
{
	echo "<select name=\"$nombre\">";
	foreach ($valores as $v) {
		echo "<option value=\"$v[0]\">$v[1]</option>";
	}
	echo "</select>";
}

function calculaEdad(string $fecha)
{
	$fecha = explode("/", $fecha);

	if (count($fecha) < 3 || count($fecha) > 3) {
		return implode("/", $fecha); // devolverá la fecha en caso de error
	}

	//deja solo números, elimina posibles carácteres que se hayan colado
	$fecha[0] = intval($fecha[0]);
	$fecha[1] = intval($fecha[1]);
	$fecha[2] = intval($fecha[2]);

	if ($fecha[2] > date("Y")) return implode("/", $fecha); // no ha nacido aún

	if (checkdate($fecha[1], $fecha[0], $fecha[2])) {
		// fecha válida
		$edad = date("Y") - $fecha[2];
		if ($fecha[1] > date("m")) {
			$edad -= 1; // resta 1 si no ha cumplido aún
		} else if ($fecha[1] == date("m")) {
			if ($fecha[0] < date("d")) {
				$edad -= 1; // resta 1 si está en el mismo mes pero no ha cumplido aún
			}
		}
		return $edad; // devolverá la edad
	}

	return implode("/", $fecha); // devolverá la fecha en caso de error
	// return false; sería otra opción
}

// escribir línea en un fichero, si no existe lo crea
// añado ../ al fichero porque está en otra carpeta diferente a "libs"
function escribeLinea(string $str, string $fichero = "archivos/frases.txt")
{
	if (!file_exists($fichero)) { // si el archivo no existe
		fclose(fopen($fichero, "a")); // crealo
	}

	if ($f = fopen($fichero, "a")) {
		fwrite($f, $str . "#" . date("d") . "-" . date("m") . "-" . date("Y") . PHP_EOL);
		fclose($f);
		return true;
	}

	return false;
}

function cNombreUsuario($usr, $campo = NULL, &$errores = NULL)
{
	if (preg_match("/^[a-zA-Z0-9]{6,16}$/", $usr)) {
		return true;
	} else {
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Usuario no válido. Solo pueden contener números y letras mayúsculas y minúsculas. Entre 6 y 16 carácteres.";
		}
		return false;
	}
}

function cEmail($email, $campo = NULL, &$errores = NULL)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Formato de email inválido.";
		}
		return false;
	}
	return true;
}

function cContrasenas($pw1, $pw2, $campo = NULL, &$errores = NULL)
{
	if ($pw1 !== $pw2) {
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Las contraseñas no coinciden.";
		}
		return false;
	}

	if (preg_match("/^[a-zA-Z0-9!\"#\$%&\/()=?¿@]{6,64}$/", $pw1) && preg_match("/^[a-zA-Z0-9!\"#\$%&\/()=?¿@]{6,64}$/", $pw2)) {
		return true;
	} else {
		if (mb_strlen($pw1) < 6 || mb_strlen($pw1) > 64 || mb_strlen($pw2) < 6 || mb_strlen($pw2) > 64) {
			if (!is_null($campo) && !is_null($errores)) {
				$errores[$campo] = "La longitud de la contraseña tiene que ser entre 6 y 64 carácteres.";
			}
			return false;
		}
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Tu contraseña contiene carácteres no admitidos. Carácteres admitidos: a-z A-Z 0-9 ! \" # $ % & / ( ) = ? ¿ @";
		}
		return false;
	}
}

function cContrasena($pw1, $campo = NULL, &$errores = NULL)
{
	if (preg_match("/^[a-zA-Z0-9!\"#\$%&\/()=?¿@]{6,64}$/", $pw1)) {
		return true;
	} else {
		if (mb_strlen($pw1) < 6 || mb_strlen($pw1) > 64) {
			if (!is_null($campo) && !is_null($errores)) {
				$errores[$campo] = "La longitud de la contraseña tiene que ser entre 6 y 64 carácteres.";
			}
			return false;
		}
		if (!is_null($campo) && !is_null($errores)) {
			$errores[$campo] = "Tu contraseña contiene carácteres no admitidos. Carácteres admitidos: a-z A-Z 0-9 ! \" # $ % & / ( ) = ? ¿ @";
		}
		return false;
	}
}

function cPrivacidadImagen($checkPrivacidad, $comentario, $pdo)
{
	
	$archivo = cFileMover("imagen", "imagenUser", $errores, $_SESSION["usuario"] . "/");

	$archivo = rawurlencode($archivo);

    //En este caso el array tiene que ser asociativo y coincidir el nombre de los parámetros con los índices
    $imagenes = [
		'idUsuario'=> $_SESSION['id'],
        'nombre' => "$archivo",
        'comentario' => "$comentario",
        'privada' => "$checkPrivacidad"
    ];
    try {
        // Preparamos la consulta
        $stmt = $pdo->prepare("INSERT INTO imagenes (idUsuario, nombre, comentario, privada) values (:idUsuario, :nombre,:comentario,:privada)");

        // Vinculamos al ejecutar utilizando el array asociativo

        if ($stmt->execute($imagenes))
            echo "El id del último usuario dado de alta es: " . $pdo->lastInsertId();
			
        } catch (PDOException $e) /*  */
        { 
            // En este caso guardamos los errores en un archivo de errores log
            error_log($e->getMessage() . "##Código: " . $e->getCode() . "  " . microtime() . PHP_EOL, 3, "../logBD.txt");
            // guardamos en ·errores el error que queremos mostrar a los usuarios
            $errores['datos'] = "Ha habido un error <br>";
        }

    $db = NULL;
}

function cUsuarioExiste($usuario, &$errores, $campo, $carpetaperfil = "fotosperfil/")
{

	$perfil = glob("$carpetaperfil/$usuario.*");
	//print_r($perfil);

	//https://stackoverflow.com/questions/3202203/finding-a-file-with-a-specific-name-with-any-extension
	//print_r($perfil);

	if (count($perfil) > 0) {
		$errores[$campo] = "Usuario ya existe.";
		return true;
		/* foreach ($perfil as $file) {
			//$info = pathinfo($file);
			// Usuario ya existe
			
			//echo "File found: extension ".$info["extension"]."<br>";
			
		} */
	} else {
		// Usuario no existe
		//echo "No file name exists called $compartment. Regardless of extension.";
		return false;
	}
}

/* flag --> 1 login - 2 register */
function cUsuarioExisteBD($usuario, &$errores = NULL, $campo = NULL, $db, $flag = 1)
{
	$consulta = "SELECT Count(*) FROM usuario where usuario='$usuario'";
	$result = $db->query($consulta)->fetchColumn();
	if ($result > 0) {
		if (!is_null($errores) && !is_null($campo)) {
			if ($flag === 2) {
				$errores[$campo] = "Ya hay un usuario registrado con ese nombre.";
				return true;
			} else if ($flag > 2) {
				throw new Exception("Flag inválido", 1);
			}
		}
		return true;
	} else {
		if ($flag === 1) {
			if (!is_null($errores) && !is_null($campo)) {
				$errores[$campo] = "Nombre de usuario no existente.";
			}
			return false;
		}
		return false;
	}
	return false;
}
	?>