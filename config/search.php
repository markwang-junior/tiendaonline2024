<?php
require_once 'database.php';

if (isset($_GET['q'])) {
    $q = $_GET['q'];

    $db = new Database();
    $con = $db->conectar();

    $sql = "SELECT nombre, precio, descuento FROM productos WHERE nombre LIKE :searchTerm";
    $stmt = $con->prepare($sql);
    $searchTerm = "%" . $q . "%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
}
?>

