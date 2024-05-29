<?php
// Incluir archivos necesarios
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';


// Establecer conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario si no está autenticado
    header('Location: login.php');
    exit;
}

// Obtener el ID de usuario de la sesión
$user_id = $_SESSION['user_id'];

// Obtener información del usuario desde la base de datos
$usuario = obtenerInformacionUsuario($user_id, $con);

// Función para obtener la información del usuario
function obtenerInformacionUsuario($user_id, $con)
{
    // Query para obtener la información del usuario
    $sql = $con->prepare("SELECT * FROM usuarios WHERE id = ?");
    $sql->execute([$user_id]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    return $usuario;
}

// Obtener información del cliente desde la base de datos
$cliente = obtenerInformacionCliente($usuario['id_cliente'], $con);

// Función para obtener la información del cliente
function obtenerInformacionCliente($cliente_id, $con)
{
    // Query para obtener la información del cliente
    $sql = $con->prepare("SELECT * FROM clientes WHERE id = ?");
    $sql->execute([$cliente_id]);
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);
    return $cliente;
}

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nuevo_nombre = $_POST['nuevo_nombre'];
    $nuevo_apellidos = $_POST['nuevo_apellidos'];
    $nuevo_email = $_POST['nuevo_email'];
    $nuevo_telefono = $_POST['nuevo_telefono'];
    $nuevo_dni = $_POST['nuevo_dni'];
    $nueva_direccion = $_POST['nueva_direccion'];
    $nuevo_codigo_postal = $_POST['nuevo_codigo_postal'];
    // Aquí recibes los demás campos del formulario...

    // Actualizar la información del cliente en la base de datos
    $sql_update_cliente = $con->prepare("UPDATE clientes SET nombres = ?, apellidos = ?, email = ?, telefono = ?, dni = ?, direccion = ?, codigopostal = ? WHERE id = ?");
    $sql_update_cliente->execute([$nuevo_nombre, $nuevo_apellidos, $nuevo_email, $nuevo_telefono, $nuevo_dni, $nueva_direccion, $nuevo_codigo_postal, $usuario['id_cliente']]);


    // Después de procesar la actualización de los datos personales
    echo '<div class="alert alert-success" role="alert">¡Tus datos se han actualizado correctamente! Serás redirigido automáticamente en unos segundos...</div>';
    echo '<meta http-equiv="refresh" content="2;url=perfil.php">';
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        /* Estilos personalizados */
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

        /* Estilo para el mensaje de error */
        .invalid-feedback {
            display: none;
            color: #dc3545;
            margin-top: .25rem;
            font-size: 80%;
        }

        /* Estilo para mostrar el mensaje de error */
        input:invalid~.invalid-feedback {
            display: block;
        }
    </style>


</head>

<body>
    <?php include 'layout/menu1.php'; ?>

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Editar Perfil</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nuevo_nombre" class="form-label">Nuevo Nombre:</label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nuevo_nombre" value="<?php echo $cliente['nombres']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_apellidos" class="form-label">Nuevos Apellidos:</label>
                        <input type="text" class="form-control" id="nuevo_apellidos" name="nuevo_apellidos" value="<?php echo $cliente['apellidos']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_email" class="form-label">Nuevo Email:</label>
                        <input type="email" class="form-control" id="nuevo_email" name="nuevo_email" value="<?php echo $cliente['email']; ?>" required>
                        <div class="invalid-feedback">Por favor, introduce un email válido.</div>
                    </div>



                    <div class="mb-3">
                        <label for="nuevo_telefono" class="form-label">Nuevo Teléfono:</label>
                        <input type="text" class="form-control" id="nuevo_telefono" name="nuevo_telefono" value="<?php echo $cliente['telefono']; ?>" pattern="[0-9]{9}" required>
                        <small id="telefonoHelp" class="form-text text-muted">El teléfono debe contener 9 dígitos.</small>
                    </div>



                    <div class="mb-3">
                        <label for="nuevo_dni" class="form-label">Nuevo DNI:</label>
                        <input type="text" class="form-control" id="nuevo_dni" name="nuevo_dni" value="<?php echo $cliente['dni']; ?>" pattern="[0-9]{8}[A-Za-z]" maxlength="9" required>
                        <small id="dniHelp" class="form-text text-muted">Introduce el DNI con 8 dígitos seguidos de una letra (sin espacios ni guiones).</small>
                    </div>

                    <div class="mb-3">
                        <label for="nueva_direccion" class="form-label">Nueva Dirección:</label>
                        <input type="text" class="form-control" id="nueva_direccion" name="nueva_direccion" value="<?php echo $cliente['direccion']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_codigo_postal" class="form-label">Nuevo Código Postal:</label>
                        <input type="text" class="form-control" id="nuevo_codigo_postal" name="nuevo_codigo_postal" value="<?php echo $cliente['codigopostal']; ?>" pattern="[0-9]{5}" required>
                        <small id="codigoPostalHelp" class="form-text text-muted">El código postal debe contener 5 dígitos.</small>
                    </div>

                    <button type="submit" name="guardarCambios" id="guardarCambios" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancelar</button>

                </form>
            </div>
        </div>
    </div>
    <?php include 'layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>