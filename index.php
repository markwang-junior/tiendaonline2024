<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'config/valoraciones.php';

// Validar los parámetros GET y asignar valores predeterminados si están vacíos o no son válidos
$idCategoria = isset($_GET['cat']) ? intval($_GET['cat']) : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';
$buscar = $_GET['q'] ?? '';

$filtro = '';

// Definir los tipos de orden permitidos y sus equivalentes en SQL
$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC',
];

// Obtener el orden correspondiente o establecer un valor predeterminado si no se proporciona uno válido
$ordenSQL = $orders[$orden] ?? 'nombre ASC';

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Consulta preparada para obtener productos con categoría y orden específicos
$sql = "SELECT id, nombre, precio, descuento, stock FROM productos WHERE activo = 1";

$params = []; // Inicializar el array de parámetros

if ($buscar != '') {
    $filtro = " AND nombre LIKE ?";
    $params[] = "%$buscar%";
}

if (!empty($idCategoria)) {
    $filtro .= " AND id_categoria = ?";
    $params[] = $idCategoria;
}

$sql .= $filtro . " ORDER BY $ordenSQL";

$stmt = $con->prepare($sql);
$stmt->execute($params);

// Obtener resultados de la consulta
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta preparada para obtener todas las categorías activas
$sqlCategorias = $con->prepare("SELECT id, nombre FROM categorias WHERE activo = 1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobiStore</title>
    <link href="css1/principal.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
</head>

<body style="background-color: #f0f5ff">
    <?php include 'layout/menu.php'; ?>

    <main style="margin-top: 20px; margin-bottom: 20px;">
        <div class="container">
            <!-- Carrusel de imágenes -->
            <div id="carouselExampleControls" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <a href="http://localhost/tiendaOnline/details.php?id=3&token=b68ac8fa63b0224c48a1ec5033a77a19561758f8">
                            <img src="images/slider-desktop-iphone-14-plus-2.jpg" class="d-block w-100" alt="Imagen 1">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a href="http://localhost/tiendaOnline/details.php?id=2&token=a2aa4aa1565d6465b4b68acd39aa795b030e94bb">
                            <img src="images/slider-desktop-iphone-15-pro-2.jpg" class="d-block w-100" alt="Imagen 2">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <img src="images/slider-desktop-mid-season-sale-mba-m2.jpg" class="d-block w-100" alt="Imagen 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <!-- Botón de categorías para pantallas pequeñas -->
                            <button class="btn btn-primary d-md-none w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
                                Categorías <i class="fas fa-chevron-down ms-auto"></i>
                            </button>
                            <!-- Lista de categorías (siempre visible en pantallas grandes) -->
                            <div class="list-group d-md-block d-none">
                                <a href="index.php" class="list-group-item list-group-item-action <?php if (empty($idCategoria)) echo 'active'; ?>">Todo</a>
                                <?php foreach ($categorias as $categoria) { ?>
                                    <?php
                                    // Consulta SQL para contar el número de productos por categoría
                                    $sqlCount = "SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?";
                                    $stmtCount = $con->prepare($sqlCount);
                                    $stmtCount->execute([$categoria['id']]);
                                    $numProductos = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
                                    ?>
                                    <a href="index.php?cat=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action <?php if ($idCategoria == $categoria['id']) echo 'active'; ?>">
                                        <?php echo $categoria['nombre']; ?>
                                        <span class="badge bg-secondary"><?php echo $numProductos; ?></span>
                                    </a>
                                <?php } ?>
                            </div>
                            <!-- Fin de la lista de categorías -->
                            <!-- Lista de categorías colapsable para pantallas pequeñas -->
                            <div class="collapse" id="collapseCategories">
                                <div class="list-group">
                                    <a href="index.php" class="list-group-item list-group-item-action <?php if (empty($idCategoria)) echo 'active'; ?>">Todo</a>
                                    <?php foreach ($categorias as $categoria) { ?>
                                        <?php
                                        // Consulta SQL para contar el número de productos por categoría
                                        $sqlCount = "SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?";
                                        $stmtCount = $con->prepare($sqlCount);
                                        $stmtCount->execute([$categoria['id']]);
                                        $numProductos = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
                                        ?>
                                        <a href="index.php?cat=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action <?php if ($idCategoria == $categoria['id']) echo 'active'; ?>">
                                            <?php echo $categoria['nombre']; ?>
                                            <span class="badge bg-secondary"><?php echo $numProductos; ?></span>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- Fin de la lista de categorías colapsable -->
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="row mb-2 justify-content-end">
                        <div class="col-12 col-md-6">
                            <form action="index.php" id="ordenForm" method="get">
                                <input type="hidden" name="cat" value="<?php echo $idCategoria; ?>">
                                <select name="orden" class="form-select form-select-sm" id="orden" onchange="submitForm()">
                                    <option selected value="">Ordenar por</option>
                                    <option value="precio_alto" <?php if ($orden === 'precio_alto') echo 'selected'; ?>>Precio más alto</option>
                                    <option value="precio_bajo" <?php if ($orden === 'precio_bajo') echo 'selected'; ?>>Precio más bajo</option>
                                    <option value="asc" <?php if ($orden === 'asc') echo 'selected'; ?>>Nombre A-Z</option>
                                    <option value="desc" <?php if ($orden === 'desc') echo 'selected'; ?>>Nombre Z-A</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- HTML y PHP para mostrar los productos -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <!-- Contenido de las tarjetas de productos -->
                        <?php foreach ($resultado as $row) : ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <!-- Contenido de cada tarjeta -->
                                    <?php
                                    $id = $row['id'];
                                    $imagen = "images/productos/" . $id . "/principal.jpg";

                                    // Verificar si la imagen existe
                                    if (!file_exists($imagen)) {
                                        $imagen = "images/no-photo.jpg";
                                    }

                                    // Calcular el precio con descuento
                                    $precio = $row['precio'];
                                    $descuento = $row['descuento'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);

                                    // Obtener valoraciones del producto
                                    $valoraciones = obtenerValoraciones($id);
                                    $promedio_valoracion = $valoraciones['promedio'] ?? 0;
                                    $cantidad_valoraciones = $valoraciones['cantidad'] ?? 0;

                                    // Calcular la cantidad de estrellas llenas y vacías
                                    $estrellasLlenas = floor($promedio_valoracion);
                                    $estrellasMedias = ($promedio_valoracion - $estrellasLlenas >= 0.5) ? 1 : 0;
                                    $estrellasVacias = 5 - $estrellasLlenas - $estrellasMedias;
                                    ?>
                                    <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" style="text-decoration: none; color: inherit; display: block; text-align: center;">
                                        <img src="<?php echo $imagen; ?>" class="card-img-top" alt="Producto" style="display: inline-block; max-height: 200px; width: auto;">
                                    </a>

                                    <div class="card-body">
                                        <div class="producto-info">
                                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                                            <?php if ($descuento > 0) : ?>
                                                <p><del><?php echo MONEDA . number_format($precio, 2, ',', '.'); ?></del></p>
                                                <p class="precio-con-descuento" style="font-size: 25px;"><?php echo MONEDA . number_format($precio_desc, 2, ',', '.') ?>
                                                    <small class="text-danger">-<?php echo $descuento ?> %</small>
                                                </p>
                                            <?php else : ?>
                                                <p class="precio-normal" style="font-size: 25px;"><?php echo MONEDA . number_format($precio, 2, ',', '.'); ?></p>
                                            <?php endif; ?>
                                            <!-- Mostrar valoraciones con estrellas -->
                                            <div class="valoracion-info">
                                                <?php for ($i = 0; $i < $estrellasLlenas; $i++) : ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php endfor; ?>
                                                <?php if ($estrellasMedias) : ?>
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                <?php endif; ?>
                                                <?php for ($i = 0; $i < $estrellasVacias; $i++) : ?>
                                                    <i class="far fa-star text-warning"></i>
                                                <?php endfor; ?>
                                                <p><?php echo number_format($promedio_valoracion, 1); ?> / 5 </p>
                                            </div>
                                            <!-- Añade el enlace que abre el modal -->
                                            <p class="text-muted mb-0" style="font-size: 12px;">
                                                <u>IVA incl. con envío gratis</u>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#politicaEnviosModal">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </p>
                                        </div>

                                        <div class="stock-info">
                                            <?php
                                            // Verificar el stock y asignar clase CSS correspondiente
                                            $stockClass = ($row['stock'] == 0) ? 'text-danger' : 'text-success';
                                            ?>
                                            <p class="card-text stock <?php echo $stockClass; ?>" style="font-size: 15px; font-weight: bold;">Stock: <?php echo $row['stock']; ?></p>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-center justify-content-md-center align-items-center align-items-md-center">
                                        <button class="btn btn-success btn-md rounded-pill" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>', 'clases/carrito.php')">
                                            <i class="fas fa-shopping-cart"></i>&nbsp; Añadir al Carrito
                                        </button>
                                    </div>

                                    <div id="mensajeExito" class="alert alert-success" style="display: none;" role="alert">
                                        Has añadido correctamente el producto al carrito.
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--Ventana de política de envío -->
    <div class="modal fade" id="politicaEnviosModal" tabindex="-1" aria-labelledby="politicaEnviosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="politicaEnviosModalLabel">Política de Envíos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Texto de la política de envíos -->
                    <p>Como parte de nuestra política de envíos para nuestra tienda online de venta exclusiva de móviles, nos comprometemos a ofrecerte un servicio rápido, seguro y eficiente. A continuación, detallamos los tipos de envío disponibles:</p>

                    <p><strong>Envío estándar:</strong> Recibe tu pedido de móviles en la puerta de tu domicilio en un plazo de 3 a 5 días hábiles. Este servicio no incluye instalación ni configuración de dispositivos.</p>

                    <p><strong>Envío exprés:</strong> Si necesitas tu móvil con urgencia, puedes optar por nuestro servicio de envío exprés y recibirlo en 24 horas hábiles. Esta opción está disponible por un costo adicional.</p>

                    <p><strong>Recogida en tienda:</strong> También ofrecemos la opción de recoger tu móvil en nuestra tienda física más cercana. Esta opción es gratuita y te permite evitar los tiempos de espera del envío.</p>

                    <p><strong>Envío internacional:</strong> Para nuestros clientes internacionales, ofrecemos envíos a diversos países con tarifas y tiempos de entrega variables. Consulta los detalles al finalizar tu compra.</p>

                    <p>Además de nuestros servicios de envío, queremos destacar que todos nuestros productos están garantizados para llegar en perfectas condiciones y listos para ser utilizados. Si tienes alguna pregunta sobre nuestros servicios de envío o necesitas más información, no dudes en contactarnos.</p>

                    <p>Además de nuestros servicios de envío, queremos destacar que todos nuestros productos están garantizados para llegar en perfectas condiciones y listos para ser utilizados. Si tienes alguna pregunta sobre nuestros servicios de envío o necesitas más información, no dudes en contactarnos.</p>

                    <p>Además de nuestros servicios de envío, queremos destacar que todos nuestros productos están garantizados para llegar en perfectas condiciones y listos para ser utilizados. Si tienes alguna pregunta sobre nuestros servicios de envío o necesitas más información, no dudes en contactarnos.</p>

                    <p>Además de nuestros servicios de envío, queremos destacar que todos nuestros productos están garantizados para llegar en perfectas condiciones y listos para ser utilizados. Si tienes alguna pregunta sobre nuestros servicios de envío o necesitas más información, no dudes en contactarnos.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function addProducto(id, token, url) {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('token', token);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart");
                        elemento.innerHTML = data.numero;
                    } else {
                        alert("No hay suficientes productos en stock");
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function submitForm() {
            document.getElementById("ordenForm").submit();
        }


        // Mostrar el mensaje tras añadir un producto correctamente al carrito
        function addProducto(id, token, url) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('token', token);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        document.getElementById("num_cart").innerHTML = data.numero;

                        // Mostrar el mensaje de éxito
                        const mensajeExito = document.getElementById('mensajeExito');
                        mensajeExito.style.display = 'block';

                        // Ocultar el mensaje después de 3 segundos
                        setTimeout(() => {
                            mensajeExito.style.display = 'none';
                        }, 3000);
                    } else {
                        alert("No hay suficientes productos en el stock");
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <?php include 'layout/footer.php'; ?>
</body>

</html>