<?php
// Se incluyen los archivos necesarios
require_once 'config/config.php'; // Archivo de configuración
require_once 'config/database.php'; // Conexión a la base de datos
require_once 'clases/clienteFunciones.php'; // Funciones relacionadas con los clientes

// Se obtienen los parámetros id y token de la URL, si están presentes
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Si alguno de los parámetros está vacío, redireccionar a la página de inicio
if ($id == '' || $token == '') {
    header('Location: index.php');
    exit;
}

// Se instancia y se establece la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Se llama a la función validaToken y se muestra el resultado
echo validaToken($id, $token, $con);
?>

<!-- Botón para regresar a index.php -->
<a href="index.php" class="btn btn-primary">Volver a la página de inicio</a>