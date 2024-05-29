<!--registrar nuevos usuarios y validamos-->
<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php'; // El nombre del archivo es clientesFunciones.php, no clienteFunciones.php

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $email = trim($_POST['email']);

    if (esNulo([$email])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }

    if (count($errors) == 0) {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente=clientes.id
                WHERE clientes.email LIKE ? Limit 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitaPassword($user_id, $con);

            if ($token !== null) {
                require_once 'clases/mailer.php';
                $mailer = new Mailer();

                $url = SITE_URL . '/reset_password.php?id=' . $user_id . '&token=' . $token;

                $asunto = "Recuperar password - Tienda Online";
                $cuerpo = "Estimado $nombres: <br> Si has solicitado el cambio de tu contraseña da clic en el siguiente link <a href='$url'>$url</a>.";
                $cuerpo .= "<br> Si no has solicitado el cambio de tu contraseña, ignora este mensaje.";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviado un correo electrónico a la dirección $email para restablecer tu contraseña.</p>";

                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta dirección de correo.";
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
    <link href="css1/estiloRecuperarPassword.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
</head>

<body>
    <?php include 'layout/menu1.php'; ?>

    <main class="form-login text-center">
        <h3>Recuperar Contraseña</h3>

        <!-- Mostrar mensajes de error aquí -->

        <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                <label for="email">Correo electrónico</label>
            </div>

            <div class="col-12 d-grid gap-3">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>

            <div class="col-12">
                ¿No tienes cuenta? <a href="registro.php">Registrarse</a>
            </div>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <?php include 'layout/footer.php'; ?>

</body>

</html>