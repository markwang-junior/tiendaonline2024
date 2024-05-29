<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_cliente'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

// Generar un token seguro para la sesión
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
$id_cliente1 = $_SESSION['user_cliente'];

// Consultar el historial de compras del cliente
$sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente1 = ? ORDER BY fecha DESC");
$sql->execute([$id_cliente1]);

// Depurar: Comprobar si se obtienen resultados
$compras = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Compras - MobiStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        .card-header {
            background-color: #285693;
            color: #fff;
            font-weight: bold;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            align-items: start;
        }

        .card-title {
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: #285693;
            border-color: #285693;
        }

        .btn-primary:hover {
            background-color: #2389a1;
            border-color: #2389a1;
        }

        .no-compras {
            text-align: center;
            margin-top: 2rem;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; ?>

    <main>
        <div class="container mt-4">
            <h2 class="mb-4">Mis compras</h2>

            <?php if (!empty($compras)) : ?>
                <?php foreach ($compras as $row) : ?>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            Compra realizada el <?php echo htmlspecialchars($row['fecha']); ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">ID de compra: <?php echo htmlspecialchars($row['id_transaccion']); ?></h5>
                            <p class="card-text">Fecha de compra: <?php echo htmlspecialchars($row['fecha']); ?></p>
                            <p class="card-text">Total: <?php echo htmlspecialchars($row['total']); ?> &euro;</p>
                            <a href="compra_detalle.php?orden=<?php echo htmlspecialchars($row['id_transaccion']); ?>&token=<?php echo htmlspecialchars($token); ?>" class="btn btn-primary">Ver compra</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>No hay compras realizadas hasta el momento.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <?php include 'layout/footer.php'; ?>

</body>

</html>
