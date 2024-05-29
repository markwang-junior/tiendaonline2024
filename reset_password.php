<!-- reset_password.php -->

<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';



if ($user_id == '' || $token == '') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!verificaTokenRequest($user_id, $token, $con)) {
    echo "No se pudo verificar la información";
    exit;
}

if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token, $password, $repassword])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (count($errors) == 0) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            echo "Contraseña actualizada.<br><a href='login.php'>Iniciar sesión</a>";
            exit;
        } else {
            $errors[] = "No se pudo actualizar la contraseña. Inténtalo nuevamente";
        }
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
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        body {
            padding-bottom: 50px;
            /* Añade espacio al final del cuerpo para el footer */
        }

        main {
            margin-top: 20px;
            /* Añade un margen superior al contenido principal */
        }

        form {
            max-width: 400px;
            /* Limita el ancho del formulario */
            margin: auto;
            /* Centra el formulario horizontalmente */
            padding: 20px;
            /* Añade espacio alrededor del formulario */
            background-color: #f8f9fa;
            /* Cambia el color de fondo del formulario */
            border-radius: 10px;
            /* Añade bordes redondeados al formulario */
        }
    </style>
</head>

<body>
    <?php include 'layout/menu1.php'; ?>

    <main>
        <div class="container">
            <h3 class="text-center pt-4">Cambiar contraseña</h3> <!-- Centra y agrega espacio superior al título -->

            <?php mostrarMensajes($errors); ?>

            <form action="reset_password.php" method="post" class="mt-4" autocomplete="off"> <!-- Agrega espacio superior al formulario -->
                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                <input type="hidden" name="token" id="token" value="<?= $token; ?>">

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required>
                    <label for="password">Nueva Contraseña</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Confirmar contraseña" required>
                    <label for="repassword">Confirmar contraseña</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="fixed-bottom bg-light text-center py-3">
        <?php include 'layout/footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

