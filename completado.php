<?php

// Se incluyen los archivos de configuración y conexión a la base de datos
require_once 'config/config.php';
require_once 'config/database.php';

// Se crea una instancia de la clase Database para establecer la conexión
$db = new Database();
$con = $db->conectar();

// Se obtiene el parámetro 'key' de la URL, si no está presente se asigna '0'
$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

$error = ''; // Variable para almacenar mensajes de error
$exito = ''; // Variable para almacenar el mensaje de éxito

// Se verifica si se recibió un ID de transacción válido
if ($id_transaccion == '') {
    $error = 'Error al procesar la petición';
} else {
    // Se verifica si existen compras completadas con el ID de transacción dado
    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status = ?");
    $sql->execute([$id_transaccion, 'COMPLETED']);

    // Si se encuentra al menos una compra completada
    if ($sql->fetchColumn() > 0) {
        // Se obtienen los detalles de la compra
        $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status =? LIMIT 1");
        $sql->execute([$id_transaccion, 'COMPLETED']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        // Se extraen datos relevantes de la compra
        $idCompra = $row['id'];
        $total = $row['total'];
        $fecha = $row['fecha'];
        $email = $row['email'];

        // Se obtienen los detalles de los productos comprados
        $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra =?");
        $sqlDet->execute([$idCompra]);

        // Se establece el mensaje de éxito
        $exito = '¡La compra se ha procesado exitosamente!';
    } else {
        $error = 'Error al comprobar la compra';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <!-- Se incluyen los estilos CSS de Bootstrap y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Se añade el favicon -->
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        .invoice-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-footer {
            text-align: right;
        }
        .btn-download {
            margin-top: 20px;
        }
        .informative-message {
            margin-top: 20px;
            text-align: center;
            background-color: #e7f3fe;
            border: 1px solid #b3d7ff;
            padding: 15px;
            border-radius: 5px;
            color: #31708f;
            font-size: 1.1em;
        }
        .informative-message i {
            margin-right: 10px;
            color: #31708f;
        }
    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; ?>

    <main>
        <div class="container" style="margin-top: 20px;">
            <?php if (strlen($error) > 0) { ?>
                <!-- Se muestra un mensaje de error si existe -->
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <?php if (!empty($exito)) { ?>
                    <!-- Se muestra un mensaje de éxito si existe -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-success" role="alert">
                                <?php echo $exito; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="invoice-container">
                    <!-- Se muestra el encabezado de la factura -->
                    <div class="invoice-header">
                        <h2>Factura de Compra</h2>
                        <p>Gracias por tu compra, <b><?php echo $email; ?></b>!</p>
                    </div>
                    <!-- Se muestran los detalles de la compra -->
                    <div class="invoice-details">
                        <div class="row">
                            <div class="col">
                                <b>Número de compra</b>: <?php echo $id_transaccion; ?><br>
                                <b>Fecha de compra</b>: <?php echo $fecha; ?><br>
                                <b>Total</b>: <?php echo MONEDA . number_format($total, 2, ',', '.'); ?><br>
                            </div>
                        </div>
                    </div>
                    <!-- Se muestra una tabla con los productos comprados -->
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                        // Se calcula el importe por producto
                                        $importe = $row_det['precio'] * $row_det['cantidad'];
                                    ?>
                                        <tr>
                                            <td><?php echo $row_det['cantidad']; ?></td>
                                            <td><?php echo $row_det['nombre']; ?></td>
                                            <td><?php echo MONEDA . number_format($importe, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     <!-- Mensaje informativo -->
                     <div class="informative-message">
                        <i class="fas fa-info-circle"></i> Te enviaremos el producto a la dirección que nos has proporcionado entre 1-3 días laborales.
                    </div>
                    <!-- Botones para descargar la factura y terminar -->
                    <div class="invoice-footer">
                        <a href="index.php" class="btn btn-success btn-lg mt-4" style="width: 200px;">
                            <i class="fas fa-check-circle"></i> Terminar
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include 'layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>

</html>

