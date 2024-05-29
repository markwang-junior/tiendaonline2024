<?php
// Incluir archivos necesarios
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

// Establecer conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Verificar si el usuario está autenticado
if (isset($_SESSION['user_id'])) {
    // Obtener el ID de usuario de la sesión
    $user_id = $_SESSION['user_id'];

    // Obtener información del usuario desde la base de datos
    $usuario = obtenerInformacionUsuario($user_id, $con);
    // Obtener información del cliente desde la base de datos
    $cliente = obtenerInformacionCliente($usuario['id_cliente'], $con);
}

// Función para obtener la información del usuario
function obtenerInformacionUsuario($user_id, $con)
{
    $sql = $con->prepare("SELECT * FROM usuarios WHERE id = ?");
    $sql->execute([$user_id]);
    return $sql->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener la información del cliente
function obtenerInformacionCliente($cliente_id, $con)
{
    $sql = $con->prepare("SELECT * FROM clientes WHERE id = ?");
    $sql->execute([$cliente_id]);
    return $sql->fetch(PDO::FETCH_ASSOC);
}

// Verificar si se envió el formulario de cambio de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["contrasenaActual"]) && isset($_POST["nuevaContrasena"]) && isset($_POST["confirmarContrasena"])) {
        $contrasenaActual = $_POST["contrasenaActual"];
        $nuevaContrasena = $_POST["nuevaContrasena"];
        $confirmarContrasena = $_POST["confirmarContrasena"];

        // Verificar la contraseña actual
        if (comprobarContraseñaActual($usuario['usuario'], $contrasenaActual, $con)) {
            if ($nuevaContrasena === $confirmarContrasena) {
                // Actualizar la contraseña
                if (actualizarContraseña($usuario['usuario'], $nuevaContrasena, $con)) {
                    $_SESSION['exito'] = "La contraseña se ha cambiado con éxito.";
                    header("Location: perfil.php");
                    exit;
                } else {
                    $error = "No se pudo actualizar la contraseña.";
                }
            } else {
                $error = "Las contraseñas no coinciden.";
            }
        } else {
            $error = "La contraseña actual es incorrecta.";
        }
        // Establecer la sesión 'error_modal' si hay un error
        if (isset($error)) {
            $_SESSION['error_modal'] = $error;
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Verificar si se estableció la sesión 'exito'
if (isset($_SESSION['exito'])) {
    // Mostrar alert de éxito
    echo '<script>alert("La contraseña se ha cambiado con éxito.");</script>';
    // Eliminar la sesión 'exito' para que no se muestre el alert nuevamente en futuras cargas de página
    unset($_SESSION['exito']);
}

// Verificar si se estableció la sesión 'error_modal'
if (isset($_SESSION['error_modal'])) {
    // Mostrar alert de error
    echo '<script>alert("Error: ' . htmlspecialchars($_SESSION['error_modal'], ENT_QUOTES, 'UTF-8') . '");</script>';
    // Eliminar la sesión 'error_modal' para que no se muestre el alert nuevamente en futuras cargas de página
    unset($_SESSION['error_modal']);
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        body {
            background-color: #f0f5ff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #333;
        }

        .card-body {
            padding: 30px;
        }

        .card-body p {
            margin-bottom: 10px;
        }

        .card-body p strong {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .modal-content {
            background-color: #fff;
        }
    </style>
</head>

<body>
    <?php include 'layout/menu1.php'; ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Mi Perfil</h2>
                <?php if (isset($usuario)) : ?>
                    <p><i class="fas fa-user"></i> Bienvenido <?php echo htmlspecialchars($usuario['usuario'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-lock"></i> Contraseña: <?php echo str_repeat('*', min(strlen($usuario['password']), 8)); ?></p>
                    <!-- Botón para abrir el modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cambiarContrasenaModal">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                <?php else : ?>
                    <p>No se encontró información de usuario.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Información personal -->
        <?php if (isset($cliente)) : ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Información personal</h2>
                    <p><i class="fas fa-user"></i> Nombre: <?php echo htmlspecialchars($cliente['nombres'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-user"></i> Apellidos: <?php echo htmlspecialchars($cliente['apellidos'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-envelope"></i> Email: <?php echo htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-phone"></i> Teléfono: <?php echo htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php
                    // Ocultar los últimos seis dígitos del DNI
                    $dni_oculto = substr($cliente['dni'], 0, -6) . '******';
                    ?>
                    <p><i class="fas fa-id-card"></i> DNI: <?php echo htmlspecialchars($dni_oculto, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> Dirección: <?php echo htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><i class="fas fa-mail-bulk"></i> Código Postal: <?php echo htmlspecialchars($cliente['codigopostal'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <!-- Botón de editar información del cliente -->
                    <a href="editar_perfil.php" class="btn btn-primary"><i class="fas fa-edit"></i> Editar </a>
                </div>
            </div>
        <?php else : ?>
            <p>No se encontró información del cliente.</p>
        <?php endif; ?>

        <!-- Modal para cambiar contraseña -->
        <div class="modal fade" id="cambiarContrasenaModal" tabindex="-1" aria-labelledby="cambiarContrasenaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cambiarContrasenaModalLabel">Cambiar Contraseña</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para cambiar contraseña -->
                        <?php if (isset($error) && $error == "La contraseña actual es incorrecta.") : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>
                        <form id="cambiarContrasenaForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validarContrasenas();">
                            <div class="mb-3">
                                <label for="contrasenaActual" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" id="contrasenaActual" name="contrasenaActual" required>
                                <div id="errorContrasenaActual" class="text-danger" style="display:none;">La contraseña actual es incorrecta.</div>
                            </div>
                            <div class="mb-3">
                                <label for="nuevaContrasena" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="nuevaContrasena" name="nuevaContrasena" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmarContrasena" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" required>
                                <div id="errorConfirmarContrasena" class="text-danger" style="display:none;">Las contraseñas no coinciden.</div>
                            </div>
                            <?php if (isset($error) && $error != "La contraseña actual es incorrecta.") : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function validarContrasenas() {
            var nuevaContrasena = document.getElementById('nuevaContrasena').value;
            var confirmarContrasena = document.getElementById('confirmarContrasena').value;
            var errorConfirmarContrasena = document.getElementById('errorConfirmarContrasena');

            if (nuevaContrasena !== confirmarContrasena) {
                errorConfirmarContrasena.style.display = 'block';
                return false;
            } else {
                errorConfirmarContrasena.style.display = 'none';
                return true;
            }
        }
    </script>

    <?php include 'layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>