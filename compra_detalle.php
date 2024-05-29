<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || $token != $token_session) {
    header('location: compras.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? limit 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowCompra['id'];

$sqlDetalle = $con->prepare("SELECT id, nombre, precio, cantidad, (precio * cantidad) AS subtotal FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idCompra]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Compra - MobiStore</title>
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

        .btn-primary {
            background-color: #285693;
            border-color: #285693;
        }

        .btn-primary:hover {
            background-color: #2389a1;
            border-color: #2389a1;
        }
    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; ?>

    <main style="margin-bottom: 20px;">
        <div class="container">
            <div class="row" style="margin-top: 20px;">
                <div class="col-12 col-md-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header" style="margin-bottom: 10px;">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($rowCompra['fecha']); ?></p>
                            <p><strong>Orden:</strong> <?php echo htmlspecialchars($rowCompra['id_transaccion']); ?></p>
                            <p><strong>Total:</strong> <?php echo MONEDA . number_format($rowCompra['total'], 2, ',', '.'); ?>&euro;</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio (&euro;)</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal (&euro;)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                        <td><?php echo number_format($row['precio'], 2, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                        <td><?php echo number_format($row['subtotal'], 2, ',', '.'); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4 justify-content-end">
                <div class="col-auto">
                    <a href="compras.php" class="btn btn-primary btn-lg">Volver a Mis Compras</a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <?php include 'layout/footer.php'; ?>
</body>

</html>