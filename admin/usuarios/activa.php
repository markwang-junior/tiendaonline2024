<?php

session_start();

require_once '../config/database.php';
require_once '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_POST['id'])) {
    // Si el ID no está presente, redirigir o manejar el error de alguna otra manera
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

$sql = $con->prepare("UPDATE usuarios SET activacion = 1 WHERE id = ?");
$sql->execute([$id]);

header('Location: index.php');

?>
