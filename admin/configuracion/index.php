<?php 
// Incluye los archivos necesarios
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../header.php';
require_once '../clases/cifrado.php';

// Verifica si el usuario está autenticado como administrador
if(!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

// Redirecciona si el usuario no es un administrador
if($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Conexión a la base de datos y obtención de la configuración
$db = new Database();
$con = $db->conectar();

$sql ="SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos =$resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datos as $dato) {
    $config[$dato['nombre']] = $dato['valor'];
}

?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Configuración</h1>

        <!-- Formulario para actualizar la configuración -->
        <form action="guarda.php" method="POST">
            <div class="row">
                <div class="col-6">
                    <label for="smtp">SMTP</label>
                    <!-- Campo de entrada para SMTP -->
                    <input class="form-control" type="text" name="smtp" id="smtp" value="<?php echo $config['correo_smtp']; ?>">
                </div>

                <div class="col-6">
                    <label for="puerto">Puerto</label>
                    <!-- Campo de entrada para el puerto -->
                    <input class="form-control" type="text" name="puerto" id="puerto" value="<?php echo $config['correo_puerto']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="email">Correo electrónico</label>
                    <!-- Campo de entrada para el correo electrónico -->
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $config['correo_email']; ?>">
                </div>

                <div class="col-6">
                    <label for="password">Contraseña</label>
                    <!-- Campo de entrada para la contraseña -->
                    <input class="form-control" type="password" name="password" id="password" value="<?php echo $config['correo_password']; ?>">
                </div>
            </div>

            <div class="row mt-4 ">
                <div class="col-12">
                    <!-- Botón de envío del formulario -->
                    <button type="submit" class="btn btn-primary"> Guardar</button>
                </div>
            </div>
        </form>

    </div>
</main>

<?php require_once '../footer.php'; ?>
