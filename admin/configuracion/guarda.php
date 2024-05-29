<?php 

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../clases/cifrado.php';
include '../header.php';

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Recibe los datos del formulario de configuración
$smtp = $_POST['smtp'];
$puerto = $_POST['puerto'];
$email = $_POST['email'];
$password = cifrar($_POST['password']);

// Actualiza la configuración en la base de datos
$sql = $con->prepare("UPDATE configuracion SET valor =? WHERE nombre =?");
$sql->execute([$smtp, "correo_smtp"]);
$sql->execute([$puerto, "correo_puerto"]);
$sql->execute([$email, "correo_email"]);
$sql->execute([$password, "correo_password"]);

?>
<main>
    <div class="container-fluid px-4">
        <!-- Mensaje de confirmación de configuración actualizada -->
        <h2 class="mt-4">Configuración actualizada</h2>
           
        <!-- Botón para regresar al panel de configuración -->
        <a href="index.php" class="btn btn-primary">Regresar</a>
    </div>
</main>
               
<?php include '../footer.php'; ?>
