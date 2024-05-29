<?php

require_once '../config/config.php';

$datos['ok'] = false;

// Verificamos si existe una sesión de carrito, si no existe la creamos
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array('productos' => array());
}

// Calculamos el número actual de elementos en el carrito
$num_cart = isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0;

// Verificamos si se ha enviado un ID y un token por POST
if (isset($_POST['id']) && isset($_POST['token'])) {
    $id = $_POST['id'];
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;
    $token = $_POST['token'];

    // Verificamos si el token es válido
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token === $token_tmp && $cantidad > 0 && is_numeric($cantidad)) {
        // Verificar disponibilidad de stock antes de agregar al carrito
        $db = new Database();
        $con = $db->conectar();
        $sql = $con->prepare("SELECT stock FROM productos WHERE id=? AND activo = 1 LIMIT 1");
        $sql->execute([$id]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);
        $stock = $producto['stock'];

        // Verificar si hay suficiente stock disponible para la cantidad deseada
        if ($stock >= $cantidad) {
            // Si el producto ya está en el carrito, aumentamos la cantidad, de lo contrario lo agregamos con cantidad 1
            if (isset($_SESSION['carrito']['productos'][$id])) {
                // Verificar si la cantidad deseada excede el stock disponible
                if ($_SESSION['carrito']['productos'][$id] + $cantidad <= $stock) {
                    $_SESSION['carrito']['productos'][$id] += $cantidad; // Aumentar la cantidad del producto existente en el carrito
                    $datos['ok'] = true;
                    $datos['mensaje'] = "Producto agregado al carrito correctamente.";
                } else {
                    $datos['ok'] = false;
                    $datos['mensaje'] = "No hay suficiente stock disponible para agregar la cantidad deseada.";
                }
            } else {
                $_SESSION['carrito']['productos'][$id] = $cantidad; // Agregar el producto al carrito con la cantidad especificada
                $datos['ok'] = true;
                $datos['mensaje'] = "Producto agregado al carrito correctamente.";
            }
            $datos['numero'] = count($_SESSION['carrito']['productos']);
        }
    }
}

// Devolvemos la respuesta JSON
echo json_encode($datos);
?>
