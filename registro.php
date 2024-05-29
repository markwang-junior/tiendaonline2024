<!--registrar nuevos usuarios y validamos-->
<?php
// Se incluyen los archivos necesarios
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

// Se establece la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Se inicializa el array de errores
$errors = [];

// Si se envió el formulario
if (!empty($_POST)) {
    // Se obtienen y se limpian los datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $direccion = trim($_POST['direccion']);
    $codigopostal = trim($_POST['codigopostal']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);


    // Se valida que todos los campos estén llenos
    if (esNulo([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repassword, $direccion, $codigopostal])) {
        $errors[] = "Debes llenar todos los campos";
    }

    // Se valida el formato del correo electrónico
    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }

    // Se valida que las contraseñas coincidan
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    // Se verifica si el usuario ya existe en la base de datos
    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario '$usuario' ya existe";
    }

    // Se verifica si el correo electrónico ya está registrado en la base de datos
    if (emailExiste($email, $con)) {
        $errors[] = "El email '$email' ya está registrado";
    }

    // Si la longitud del código postal es menor que 5, significa que es un código postal incompleto
    // Se agrega un cero al principio para completar el código postal
    if (strlen($codigopostal) < 5) {
        $codigopostal = '0' . $codigopostal;
    }

    // Se valida el formato del DNI
    if (!validarDNI($dni)) {
        $errors[] = "El formato del DNI no es válido.";
    }

    // Se valida el formato del número de teléfono
    if (!validarTelefono($telefono)) {
        $errors[] = "El número de teléfono no es válido.";
    }



    // Si no hay errores, se procede a registrar al cliente y al usuario
    if (count($errors) == 0) {
        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $dni, $direccion, $codigopostal], $con);

        if ($id > 0) {
            $token = generarToken();
            $password_cifrada = password_hash($password, PASSWORD_DEFAULT);
            $idUsuario = registraUsuario([$usuario, $password_cifrada, $token, $id], $con);

            if ($idUsuario > 0) {
                // Se envía un correo electrónico para activar la cuenta
                require_once 'clases/mailer.php';
                $mailer = new Mailer();
                $url = SITE_URL . '/activa_cliente.php?id=' . $idUsuario . '&token=' . $token;
                $asunto = "Activar cuenta - Tienda Online";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es indispensable hacer clic en la siguiente liga <a href='$url'>Activar cuenta</a>";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "Para terminar el proceso de registro, siga las instrucciones que se le enviaron al correo $email";
                    exit;
                } else {
                    $errors[] = "Error al enviar correo";
                }
            } else {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Nuevo Cliente - MobiStore</title>
    <link href="css1/principal.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
</head>

<body style="background-color: #f0f5ff">
    <?php include 'layout/menu1.php'; ?>

    <main style="margin-top: 20px; margin-bottom:20px">
        <div class="container">
            <h2>Registro de Nuevo Cliente</h2>
            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="nombres" class="form-label"><span class="text-danger">*</span>Nombres:</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="apellidos" class="form-label"><span class="text-danger">*</span>Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label"><span class="text-danger">*</span>Correo Electrónico:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="telefono" class="form-label"><span class="text-danger">*</span>Teléfono:</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="dni" class="form-label"><span class="text-danger">*</span>DNI:</label>
                    <input type="text" name="dni" id="dni" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="usuario" class="form-label"><span class="text-danger">*</span>Usuario:</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label"><span class="text-danger">*</span>Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="repassword" class="form-label"><span class="text-danger">*</span>Repetir Contraseña:</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="direccion" class="form-label"><span class="text-danger">*</span>Dirección:</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="codigopostal" class="form-label"><span class="text-danger">*</span>Código Postal:</label>
                    <input type="text" name="codigopostal" id="codigopostal" class="form-control" required>
                </div>

                <div class="col-12">
                    <p class="text-danger"><i>Nota: Los campos marcados con * son obligatorios.</i></p>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <?php include 'layout/footer.php'; ?>
</body>