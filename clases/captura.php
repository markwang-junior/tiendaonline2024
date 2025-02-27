<?php 

require_once '../config/config.php';
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

echo '<pre>';
print_r($datos);
echo '<pre>';

if(is_array($datos) && isset($datos['detalles'])) {
    // Obtener los datos del arreglo $datos
    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $update_time  = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($update_time));
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id']; // ID de PayPal
    $id_cliente1 = $_SESSION['user_cliente']; // ID único del cliente en tu tabla de clientes

    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, id_cliente1, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $id_cliente1, $total]);
    $id = $con->lastInsertId();

    if($id > 0){
        $productos_carrito = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
    
        if($productos_carrito != null){
            foreach($productos_carrito as $clave => $cantidad) {
    
                $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo = 1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);
                
                $precio =   $row_prod['precio'];
                $descuento =  $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
    
                $sql_insert = $con->prepare("INSERT INTO detalle_compra(id_compra, id_producto, nombre, precio, cantidad) VALUES(?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['nombre'],$precio_desc, $cantidad]);
    
                // Llamar a la función restarStock para reducir el stock del producto
                restarStock($clave, $cantidad, $con);
    
            }
            include 'enviar_email.php';
        }
        unset($_SESSION['carrito']); // Eliminar el carrito de la sesión
    }
    
}   

function restarStock($id, $cantidad, $con) 
{
    $sql = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id =?");
    $sql->execute([$cantidad, $id]);
}   
?>
