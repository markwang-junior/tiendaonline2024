<?php 
require_once '../config/config.php';
require_once '../config/database.php';

if(!isset($_SESSION['user_type'])) {
    header ('Location: index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin') {
    header ('Location: ../index.php');
    exit;
}

// Verificar si se recibió el ID del producto
if(isset($_POST['id'])) {
    $id = $_POST['id'];

    $db = new Database();
    $con = $db->conectar();

    $sql = $con->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
    $sql->execute([$id]);

    header('Location: index.php');
    exit;
} else {
    // Manejar el caso donde no se recibió el ID del producto
    echo "Error: No se recibió el ID del producto.";
}
?>
