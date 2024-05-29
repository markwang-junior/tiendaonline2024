<?php 

require_once '../config/database.php';
require_once '../config/config.php';

session_start(); // Asegúrate de que la sesión esté iniciada

if(!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$descripcion1 = $_POST['descripcion1'];
$precio = $_POST['precio'];
$descuento = $_POST['descuento'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

// Preparamos la consulta para insertar un nuevo producto en la tabla de productos
$sql = "INSERT INTO productos (nombre, descripcion, descripcion1, precio, descuento, stock, id_categoria, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stm = $con->prepare($sql);

// Ejecutamos la consulta con los valores proporcionados
if($stm->execute([$nombre, $descripcion, $descripcion1, $precio, $descuento, $stock, $categoria, 1])) {
    // Obtenemos el ID del producto recién insertado
    $id = $con->lastInsertId();

    /* Subir imagen principal */
    if(isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] == UPLOAD_ERR_OK) {
        $dir = '../../images/productos/'. $id .'/';
        $permitidos = ['jpeg', 'jpg', 'png'];

        $arregloImagen = explode('.', $_FILES['imagen_principal']['name']);
        $extension = strtolower(end($arregloImagen));

        if(in_array($extension, $permitidos)) {
            if(!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $ruta_img = $dir . 'principal.' . $extension;
            if(move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_img)) {
                echo 'El archivo se cargó correctamente.';
            } else {
                echo 'No se pudo cargar el archivo.';
            }
        } else {
            echo 'La extensión del archivo no es válida.';
        }
    } else {
        echo 'No enviaste el archivo.';
    }

    /* Subir otras imagenes */
    if(isset($_FILES['otras_imagenes'])) {
        $dir = '../../images/productos/'. $id .'/';
        $permitidos = ['jpeg', 'jpg', 'png'];
        
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $contador = 1;
        foreach($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['otras_imagenes']['name'][$key];

            $arregloImagen = explode('.', $fileName);
            $extension = strtolower(end($arregloImagen));

            if(in_array($extension, $permitidos)) {
                $ruta_img = $dir . $contador . '.' . $extension;
                if(move_uploaded_file($tmp_name, $ruta_img)) {
                    echo 'El archivo ' . $contador . ' se cargó correctamente.';
                    $contador++;
                } else {
                    echo 'No se pudo cargar el archivo ' . $contador . '.';
                }
            } else {
                echo 'La extensión del archivo ' . $contador . ' no es válida.';
            }
        }
    }
}

// Redireccionamos a la página principal o a donde sea necesario
header('Location: index.php');

?>
