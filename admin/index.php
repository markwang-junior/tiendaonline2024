<?php 

require 'config/config.php';
require 'config/database.php';
require 'clases/adminFunciones.php';

$db = new Database();
$con = $db->conectar();

/*$password = password_hash('admin', PASSWORD_DEFAULT);
$sql = "INSERT INTO admin (usuario, password, nombre, email, activo, fecha_alta ) VALUES ('admin','$password','Administrador','mark.wg2001@gmail.com','1',now())";
$con->query($sql);*/


$errors = [];

if(!empty($_POST)){
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debes llenar todos los campos";
    }

    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Inicio de sesión - MobiStore</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        body {
            background-image: url('images/imgFondo.jpg'); 
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Administración de Tienda</h3></div>
                                <div class="card-body">
                                    <h4 class="text-center mb-4">Iniciar Sesión</h4>
                                    <form action="index.php" method="POST" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" name="usuario"  type="text" placeholder="usuario" required autofocus/>
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" name="password" type="password" placeholder="Password" required />
                                            <label for="password">Contraseña</label>
                                        </div>
                                        
                                        <?php echo mostrarMensajes($errors);?>

                                        <div class="d-flex justify-content-center mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                                        </div>

                                        <hr>
                                        <div class="text-center mt-3">
                                            <a href="http://localhost/tiendaOnline/index.php" class="text-decoration-underline">Regresar a la Tienda</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; MobileStore <?php echo date('Y'); ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

