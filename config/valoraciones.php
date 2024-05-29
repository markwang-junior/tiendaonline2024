<?php
require_once 'database.php';

function obtenerValoraciones($producto_id) {
    $db = new Database();
    $conexion = $db->conectar();

    $sql = "SELECT AVG(valoracion) as promedio, COUNT(valoracion) as cantidad FROM valoraciones WHERE producto_id = :producto_id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado : ['promedio' => 0, 'cantidad' => 0];
}

function anadirValoracion($producto_id, $usuario_id, $valoracion, $comentario) {
    $db = new Database();
    $conexion = $db->conectar();

    $sql = "INSERT INTO valoraciones (producto_id, usuario_id, valoracion, comentario) VALUES (:producto_id, :usuario_id, :valoracion, :comentario)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':valoracion', $valoracion, PDO::PARAM_INT);
    $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
    return $stmt->execute();
}
?>
