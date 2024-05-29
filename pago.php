<?php
// Se incluyen los archivos de configuración y conexión a la base de datos
require_once 'config/config.php';
require_once 'config/database.php';

// Se crea una instancia de la clase Database para establecer la conexión
$db = new Database();
$con = $db->conectar();

// Se obtiene el carrito de productos almacenado en la sesión
$productos_carrito = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

// Se inicializa una lista para almacenar los detalles de los productos en el carrito
$lista_carrito = array();

// Si hay productos en el carrito, se recorren para obtener sus detalles de la base de datos
if ($productos_carrito != null) {
    foreach ($productos_carrito as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo = 1");
        $sql->execute([$clave]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el producto, se agrega a la lista de productos en el carrito
        if ($producto) {
            $producto['cantidad'] = $cantidad;
            $lista_carrito[] = $producto;
        }
    }
} else {
    // Si no hay productos en el carrito, se redirige al usuario a la página de inicio
    header("location: index.php");
    exit;
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
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .info-section h5 {
            margin: 0 0 10px;
        }

        .info-section i {
            color: #007bff;
            margin-right: 8px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .total-section {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
        }

        .paypal-button-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; ?>

    <main style="margin-top: 20px;">
        <div class="container">
            <!-- Sección de información sobre envío -->
            <div class="info-section text-center">
                <h5><i class="fas fa-shipping-fast"></i> Envío gratis en pedidos superiores a €50</h5>
                <h5><i class="fas fa-clock"></i> Envío en 24-72 horas</h5>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Sección de detalles de pago con botón de PayPal -->
                    <h4>Detalles de pago</h4>
                    <div id="paypal-button-container" class="paypal-button-container"></div>
                </div>

                <div class="col-md-6">
                    <!-- Sección del carrito de compras -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="col-8">Producto</th>
                                    <th scope="col" class="col-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_carrito == null) {
                                    echo '<tr><td colspan="2" class="text-center"><b>Lista vacía</b></td></tr>';
                                } else {
                                    // Se calcula el total de la compra mientras se muestran los productos en el carrito
                                    $total = 0;
                                    foreach ($lista_carrito as $producto) {
                                        $_id = $producto['id'];
                                        $nombre = $producto['nombre'];
                                        $precio = $producto['precio'];
                                        $descuento = $producto['descuento'];
                                        $cantidad = $producto['cantidad'];
                                        $precio_desc = $precio - (($precio * $descuento) / 100);
                                        $subtotal = $cantidad * $precio_desc;
                                        $total += $subtotal;
                                ?>
                                        <tr>
                                            <td class="col-8"><?php echo $nombre; ?></td>
                                            <td class="col-4">
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, ',', '.'); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <!-- Se muestra el total de la compra -->
                                    <tr>
                                        <td colspan="4" class="total-section">
                                            <p id="total" style="font-size: 1.5rem; font-weight: bold; text-align: right; color: #000;">
                                                Total: <?php echo MONEDA . number_format($total, 2, ',', '.'); ?>
                                            </p>
                                        </td>
                                    </tr>



                            </tbody>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>&locale=es_ES"></script>

    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
                        }
                    }]
                });
            },

            onApprove: function(data, actions) {
                let URL = 'clases/captura.php';
                actions.order.capture().then(function(detalles) {

                    console.log(detalles);

                    let url = 'clases/captura.php';

                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response) {
                        window.location.href = "completado.php?key=" + detalles['id'];
                    })
                });
            },

            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>

    <?php include 'layout/footer.php'; ?>

</body>

</html>