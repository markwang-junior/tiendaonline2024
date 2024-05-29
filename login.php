<!--registrar nuevos usuarios y validamos-->
<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) {

    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';;


    if (esNulo([$usuario, $password])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="css1/estilosIniciaSesion.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
</head>

<body>
    <?php include 'layout/menu1.php'; ?>

    <main class="form-login">
        <h2>Iniciar Sesión</h2>

        <?php mostrarMensajes($errors); ?>

        <form action="login.php" method="POST" autocomplete="off">
            <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder=" ">
                <label for="usuario">Usuario</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder=" ">
                <label for="password">Contraseña</label>
            </div>

            <div class="forgot-password">
                <a href="recupera.php">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="col-12 d-grid gap-3">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>

            <hr>

            <div class="register-link">
                ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
            </div>
        </form>

        <div class="admin-panel-btn">
            <a href="http://localhost/tiendaOnline/admin/" class="btn btn-outline-primary">Panel de Administración</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <?php include 'layout/footer.php'; ?>

</body>

</html>