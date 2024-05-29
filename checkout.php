<?php
// Se incluyen los archivos de configuración y las clases necesarias
require_once 'config/config.php';
require_once 'config/database.php';

// Se establece la conexión con la base de datos
$db = new Database();
$con = $db->conectar();

// Se obtienen los productos del carrito y el ID del cliente de la sesión
$producto = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$cliente_id = isset($_SESSION['user_cliente']) ? $_SESSION['user_cliente'] : null;

// Se inicializan las variables para almacenar el carrito y los datos del cliente
$lista_carrito = array();
$cliente = null;

// Se verifica si hay productos en el carrito
if ($producto != null) {
    // Se recorren los productos del carrito para obtener información adicional
    foreach ($producto as $clave => $cantidad) {
        // Se prepara y ejecuta una consulta para obtener los detalles del producto
        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo = 1");
        $sql->execute([$clave]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el producto, se agrega al carrito
        if ($producto) {
            $producto['cantidad'] = $cantidad;
            $lista_carrito[] = $producto;
        }
    }
}

// Se obtienen los datos del cliente si está autenticado
if ($cliente_id != null) {
    $sql = $con->prepare("SELECT nombres, apellidos, telefono, direccion, codigopostal FROM clientes WHERE id=?");
    $sql->execute([$cliente_id]);
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css1/checkout.css">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            color: #333;
        }

        .table td {
            background-color: #fff;
            color: #555;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-eliminar {
            color: #fff;
            background-color: #dc3545;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .btn-pago {
            color: #fff;
            background-color: #007bff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
        }

        .btn-pago:hover {
            background-color: #0056b3;
        }

        .btn-fixed {
            position: fixed;
            bottom: 0;
            right: 0;
            left: 0;
            width: 100%;
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            z-index: 1000;
        }

        .btn-fixed:hover {
            background-color: #0056b3;
        }

        .content-bottom-margin {
            margin-bottom: 80px;
        }

        .icono {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include 'layout/menu1.php'; ?>

    <main class="content-bottom-margin" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box icono"></i>Producto</th>
                            <th><i class="fas fa-tag icono"></i>Precio</th>
                            <th><i class="fas fa-sort-amount-up icono"></i>Cantidad</th>
                            <th><i class="fas fa-money-bill icono"></i>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                        } else {
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
                                    <td><?php echo $nombre; ?></td>
                                    <td><?php echo MONEDA . number_format($precio_desc, 2, ',', '.'); ?></td>
                                    <td>
                                        <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, '<?php echo $_id; ?>')">
                                    </td>
                                    <td>
                                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]" style="font-weight: bold;"><?php echo MONEDA . number_format($subtotal, 2, ',', '.'); ?></div>
                                    </td>
                                    <td>
                                        <a href="#" id="eliminar" class="btn btn-danger btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                            <p style="visibility: hidden;" class="h6" id="total">Total: <?php echo MONEDA . number_format($total, 2, ',', '.'); ?></p>
                    </tbody>
                <?php } ?>
                </table>
            </div>

            <?php if ($lista_carrito !== null) {
                if (empty($lista_carrito)) {
                    echo '<div class="alert alert-warning mt-3" role="alert">Tu carrito está vacío. Por favor, agrega algunos productos antes de continuar.</div>';
                } else {
            ?>
                    <div class="row mt-3">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-file-invoice icono"></i>Dirección de Facturación</h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($cliente != null) { ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th><i class="fas fa-user icono"></i>Nombres</th>
                                                        <td><?php echo htmlspecialchars($cliente['nombres']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-user icono"></i>Apellidos</th>
                                                        <td><?php echo htmlspecialchars($cliente['apellidos']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-phone icono"></i>Teléfono</th>
                                                        <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-map-marker-alt icono"></i>Dirección de Envío</th>
                                                        <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-mail-bulk icono"></i>Código Postal</th>
                                                        <td><?php echo htmlspecialchars($cliente['codigopostal']); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <p>Debes iniciar sesión para poder visualizar los datos de la dirección de facturación.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h4><strong><i class="fas fa-shopping-cart icono"></i>Resumen del Pedido</strong></h4>
                        <ul class="list-group">
                            <?php foreach ($lista_carrito as $producto) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $producto['nombre']; ?>
                                    <span><?php echo MONEDA . number_format($producto['precio'] - (($producto['precio'] * $producto['descuento']) / 100), 2, ',', '.'); ?> x <?php echo $producto['cantidad']; ?></span>
                                </li>
                            <?php } ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Total</strong>
                                <span><strong style="font-size: 20px;"><i class="fas fa-coins"></i> <?php echo MONEDA . number_format($total, 2, ',', '.'); ?></strong></span>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-5 d-grid gap-2 mt-3 mt-md-0">
                        <?php if (isset($_SESSION['user_cliente'])) { ?>
                            <a href="pago.php" class="btn btn-primary btn-lg btn-fixed">Realizar pago</a>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-primary btn-lg btn-fixed">Iniciar sesión para pagar</a>
                        <?php } ?>
                    </div>
        </div>
<?php }
            } ?>
    </main>

    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminaModalLabel">Eliminar producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el producto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Redirigiendo a la página de pago...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnRealizarPago = document.querySelector(".btn-primary.btn-lg.btn-fixed");
            if (btnRealizarPago) {
                btnRealizarPago.addEventListener("click", function(event) {
                    event.preventDefault();
                    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                    loadingModal.show();
                    setTimeout(() => {
                        window.location.href = btnRealizarPago.getAttribute("href");
                    }, 2000);
                });
            }

            actualizaEnvio(); // Llama a la función cuando la página se carga para asegurarse de que el envío esté actualizado
        });

        let eliminaModal = document.getElementById('eliminaModal');
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let id = button.getAttribute('data-bs-id');
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina');
            buttonElimina.value = id;
        });

        function actualizaCantidad(cantidad, id) {
            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'agregar');
            formData.append('id', id);
            formData.append('cantidad', cantidad);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload(); // Recarga la página después de actualizar la cantidad
                    } else {
                        let inputCantidad = document.getElementById('cantidad_' + id);
                        inputCantidad.value = data.cantidadAnterior;
                        alert("No hay suficientes productos en stock");
                    }
                });
        }

        function eliminar() {
            let botonEliminar = document.getElementById('btn-elimina');
            let id = botonEliminar.value;
            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload(); // Recarga la página después de eliminar un producto
                    }
                });
        }
    </script>

    <?php include 'layout/footer.php'; ?>

</body>

</html>

