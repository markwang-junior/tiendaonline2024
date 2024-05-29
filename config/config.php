<?php 

$path = dirname(__FILE__);

require_once $path . '/database.php';
require_once $path . '/../admin/clases/cifrado.php';


$db = new Database();
$con = $db->conectar();

$sql ="SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datosConfig =$resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datosConfig as $datoConfig) {
    $config[$datoConfig['nombre']] = $datoConfig['valor'];
}

define("SITE_URL", "http://localhost/tiendaOnline/");
define("KEY_TOKEN", "APR.wqc-354*");
define ("MONEDA", "€");


define("CLIENT_ID", "AUZcceC8n3RI8_q-Ejrlck0XTIzJvOp82tb-BL2Dq63ZHc1LsucabSAJfhcWivVjuaXQ33h85EuhAZcc");
define("CURRENCY", "EUR");

define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS",  descifrar($config['correo_password']));
define("MAIL_PORT",  $config['correo_puerto']);

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>


