<?php 

require_once '../config/database.php';
require_once '../config/config.php';

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

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$descripcion1 = $_POST['descripcion1'];
$precio = $_POST['precio'];
$descuento = $_POST['descuento'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

// Preparamos la consulta para actualizar un producto en la tabla de productos
$sql = "UPDATE productos SET nombre=?, descripcion=?, descripcion1=?, precio=?, descuento=?, stock=?, id_categoria=? WHERE id=?";
$stm = $con->prepare($sql);

// Ejecutamos la consulta con los valores proporcionados
if($stm->execute([$nombre, $descripcion, $descripcion1, $precio, $descuento, $stock, $categoria, $id])) {
    /* Subir imagen principal */
    if(isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] == UPLOAD_ERR_OK) {
        $dir = '../../images/productos/'. $id .'/';
        $permitidos = ['jpeg', 'jpg', 'png'];

        $arregloImagen = explode('.' , $_FILES['imagen_principal']['name']);
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
        $contador = 1; // Inicializamos el contador

        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['otras_imagenes']['name'][$key];

            $arregloImagen = explode('.' , $fileName);
            $extension = strtolower(end($arregloImagen));

            $nuevoNombre = $dir . uniqid() . '.' . $extension;

            if(in_array($extension, $permitidos)) {
                if(move_uploaded_file($tmp_name, $nuevoNombre)) {
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
