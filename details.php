<?php
// Se incluyen los archivos de configuración y conexión a la base de datos
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'config/valoraciones.php';

// Se crea una instancia de la clase Database para establecer la conexión
$db = new Database();
$con = $db->conectar();

// Se obtienen los parámetros de la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Se verifica si se proporcionaron los parámetros necesarios
if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
} else {
    // Se genera un token temporal utilizando la clave secreta del token y el ID del producto
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    // Se compara el token proporcionado con el token temporal
    if ($token == $token_tmp) {
        // Se verifica si el producto con el ID especificado está activo en la base de datos
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo = 1");
        $sql->execute([$id]);

        // Si se encuentra el producto, se recuperan sus detalles
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT nombre, descripcion, descripcion1, precio, descuento, stock FROM productos WHERE id=? AND activo = 1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $descripcion1 = $row['descripcion1'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_images = 'images/productos/' . $id . '/';
            $rutaImg = $dir_images . 'principal.jpg';
            $valoraciones = obtenerValoraciones($id);
            $promedio_valoracion = $valoraciones['promedio'];
            $cantidad_valoraciones = $valoraciones['cantidad'];

            // Verificar si el método de solicitud es POST y si hay una sesión de usuario activa
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
                // Obtener el ID del usuario de la sesión
                $usuario_id = $_SESSION['user_id'];

                // Verificar si el usuario ya ha dejado una valoración para este producto
                $sql_check = $con->prepare("SELECT COUNT(*) FROM valoraciones WHERE producto_id = ? AND usuario_id = ?");
                $sql_check->execute([$id, $usuario_id]);
                $existing_reviews = $sql_check->fetchColumn();

                if ($existing_reviews > 0) {
                    // El usuario ya ha dejado una valoración
                    echo '<div class="alert alert-warning alert-animation" role="alert">Ya has dejado una valoración para este producto.</div>';
                } else {
                    // El usuario no ha dejado una valoración, permite agregar una nueva
                    $valoracion = $_POST['valoracion'];
                    $comentario = $_POST['comentario'];

                    if (anadirValoracion($id, $usuario_id, $valoracion, $comentario)) {
                        echo '<div id="successMessage" class="alert alert-success alert-animation" role="alert">Gracias por tu valoración.</div>';
                    } else {
                        echo '<div class="alert alert-danger alert-animation" role="alert">Error al enviar la valoración.</div>';
                    }
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // No hay una sesión de usuario activa, mostrar un mensaje de error
                echo '<div class="alert alert-danger alert-animation" role="alert">Debes iniciar sesión para dejar una valoración.</div>';
            }


            // Realizar consulta a la base de datos para obtener las valoraciones del producto
            $sql_valoraciones = $con->prepare("SELECT * FROM valoraciones WHERE producto_id = ?");
            $sql_valoraciones->execute([$id]);
            $valoraciones_producto = $sql_valoraciones->fetchAll(PDO::FETCH_ASSOC);

            // Se verifica si existe una imagen principal para el producto
            if (!file_exists($rutaImg)) {
                $rutaImg = 'images/no-photo.jpg';
            }

            // Se buscan y almacenan otras imágenes asociadas al producto
            $imagenes = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);

                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') !== false || strpos($archivo, 'jpeg') !== false)) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }
        }
    } else {
        // Si el token no coincide, se muestra un mensaje de error
        echo 'Error al procesar la petición';
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css1/details.css">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">

    <style>
        .hide-message {
            animation: fadeOut 1s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>

</head>

<body style="background-color: white;">
    <?php include 'layout/menu1.php'; ?>
    <main style="margin-top: 20px; margin-bottom: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <!-- Agrega el atributo data-toggle="lightbox" a las imágenes del carrusel -->
                    <div id="carouselImages" class="carousel slide carousel-fade">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img id="mainImage" src="<?php echo $rutaImg; ?>" class="d-block w-100" data-toggle="lightbox" data-gallery="example-gallery">
                            </div>

                            <?php foreach ($imagenes as $key => $img) : ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $img; ?>" class="d-block w-100" data-toggle="lightbox" data-gallery="example-gallery" data-image-index="<?php echo $key; ?>">
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>


                    <!-- Miniaturas de las imágenes adicionales dentro del mismo contenedor -->
                    <div class="row mt-3 mt-md-0" id="thumbnailRow">
                        <?php foreach ($imagenes as $key => $img) : ?>
                            <div class="col-3">
                                <img src="<?php echo $img; ?>" class="img-thumbnail thumbnail" alt="Imagen" data-thumbnail-index="<?php echo $key; ?>">
                            </div>
                        <?php endforeach ?>

                        <!-- Agregar los párrafos con los iconos de Font Awesome -->
                        <hr>
                        <div class="col-12">
                            <p><strong><i class="fas fa-star"></i> <span style="margin-left: 20px;">Nuevo y 100% Original</span></strong></p>
                            <hr>
                            <p><i class="fas fa-shield-alt"></i> <span style="margin-left: 20px;"> Financiación 60 plazos 0% TIN.</span>
                                <hr>
                            <p><i class="fas fa-lock"></i> <span style="margin-left: 20px;">*Garantía 3 años.
                                    <hr>
                                    <p><i class="fas fa-undo"></i> <span style="margin-left: 20px;"> *Devolución 30 días.
                        </div>
                        <hr>
                    </div>
                </div>

                <!--Valoracion del producto-->
                <div class="col-md-6 order-md-2">
                    <h2 class="mt-3 mb-md-4"><?php echo $nombre ?></h2>

                    <div class="container">
                        <div class="valoracion-info my-4">
                            <?php for ($i = 0; $i < floor($promedio_valoracion); $i++) : ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php endfor; ?>
                            <?php if ($promedio_valoracion - floor($promedio_valoracion) >= 0.5) : ?>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < 5 - floor($promedio_valoracion) - ($promedio_valoracion - floor($promedio_valoracion) >= 0.5 ? 1 : 0); $i++) : ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endfor; ?>
                            <p class="d-inline"><?= number_format($promedio_valoracion, 1) ?> / 5 (<?= htmlspecialchars($cantidad_valoraciones) ?> valoraciones)</p>
                            <p><a href="#section-valoracion">Ver valoraciones</a></p>

                        </div>
                    </div>

                    <!--Descuento del producto -->
                    <?php if ($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 2, ',', '.') ?></del></p>
                        <h2 class="text-danger"><?php echo MONEDA . number_format($precio_desc, 2, ',', '.') ?>
                            <small class="text-success font-weight-bold">-<?php echo $descuento ?>% Descuento</small>
                        </h2>
                    <?php } else { ?>
                        <h2><?php echo MONEDA . number_format($precio, 2, ',', '.') ?></h2>
                    <?php } ?>

                    <!-- Mover el texto "Impuestos Incluidos" dentro del bloque condicional -->
                    <?php if ($descuento > 0) : ?>
                        <p><small>Impuestos Incluidos</small></p>
                    <?php endif; ?>

                    <p class="lead">
                        <?php echo $descripcion ?>
                    </p>

                    <div class="col-3 my-3">
                        <b>Cantidad:</b> <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="100" value="1">
                        <br>
                        <?php if ($row['stock'] > 0) : ?>
                            <!-- Icono de check para En Stock -->
                            <strong><i class="fas fa-check text-success"></i><span class="ms-2 text-success">En Stock</span></strong>
                        <?php else : ?>
                            <!-- Icono de cruz para Sin Stock -->
                            <strong><i class="fas fa-times text-danger"></i><span class="ms-2 text-danger">Sin Stock</span></strong>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" id="btnCompraAhora" type="button">Comprar ahora</button>
                        <button class="btn btn-outline-primary" id="btnAgregar" type="button">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($descripcion1)) : ?>
            <div class="container-fluid py-5 mt-4" style="background-color: #f2f2f2;">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="text-center">Información Adicional</h3>
                            <p class="lead text-center"><?php echo $descripcion1; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- valoracion del producto -->
        <div class="container" id="section-valoracion">
            <div class="valoracion-info my-4">
                <?php for ($i = 0; $i < floor($promedio_valoracion); $i++) : ?>
                    <i class="fas fa-star text-warning"></i>
                <?php endfor; ?>
                <?php if ($promedio_valoracion - floor($promedio_valoracion) >= 0.5) : ?>
                    <i class="fas fa-star-half-alt text-warning"></i>
                <?php endif; ?>
                <?php for ($i = 0; $i < 5 - floor($promedio_valoracion) - ($promedio_valoracion - floor($promedio_valoracion) >= 0.5 ? 1 : 0); $i++) : ?>
                    <i class="far fa-star text-warning"></i>
                <?php endfor; ?>
                <p><?= number_format($promedio_valoracion, 1) ?> / 5 (<?= htmlspecialchars($cantidad_valoraciones) ?> valoraciones)</p>
            </div>

            <!-- Formulario de valoración -->
            <div class="mt-5">
                <h3>Deja tu valoración</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="valoracion">Valoración</label>
                        <select class="form-control" id="valoracion" name="valoracion" required>
                            <option value="5">5 estrellas</option>
                            <option value="4">4 estrellas</option>
                            <option value="3">3 estrellas</option>
                            <option value="2">2 estrellas</option>
                            <option value="1">1 estrella</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comentario">Comentario</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary " style="margin-top: 20px;">Enviar valoración</button>
                </form>
            </div>
        </div>
          
        <!-- Mostrar las valoraciones del producto -->
        <?php if (!empty($valoraciones_producto)) : ?>
            <div class="container mt-5">
                <h3>Valoraciones de los usuarios</h3>
                <div class="row">
                    <?php foreach ($valoraciones_producto as $valoracion) : ?>
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Valoración:
                                        <?php for ($i = 0; $i < $valoracion['valoracion']; $i++) : ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php endfor; ?>
                                        <?php for ($i = $valoracion['valoracion']; $i < 5; $i++) : ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endfor; ?>
                                    </h5>
                                    <p class="card-text"><?= $valoracion['comentario'] ?></p>
                                    <p class="card-text"><small class="text-muted">Fecha: <?= $valoracion['fecha'] ?></small></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <button onclick="scrollToTop()" id="btnScrollToTop" title="Ir arriba" style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; font-size: 24px;">
            <i class="fas fa-arrow-up"></i>
        </button>

        <!-- Modal de carga -->
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

        <!-- Toast de notificación -->
        <div id="toastContainer" class="position-fixed" style="top: 30px; left: 50%; transform: translateX(-50%); z-index: 1055;">
            <div id="toastAddToCart" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Producto agregado al carrito correctamente.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!--Peticion AJAX-->
    <script>
        // Función para cambiar la imagen principal
        function changeMainImage(selectedImageUrl) {
            document.getElementById('mainImage').src = selectedImageUrl;
        }

        // Función para agregar los event listeners a las miniaturas
        function addThumbnailListeners() {
            document.querySelectorAll('.thumbnail').forEach(thumbnail => {
                thumbnail.addEventListener('click', () => {
                    changeMainImage(thumbnail.src);
                });
            });
        }

        // Llamar a la función para agregar los event listeners a las miniaturas inicialmente
        addThumbnailListeners();

        // Obtener el carrusel de imágenes
        const carouselImages = document.getElementById('carouselImages');

        // Agregar un controlador de eventos al evento 'slid.bs.carousel'
        carouselImages.addEventListener('slid.bs.carousel', () => {
            const selectedImageUrl = carouselImages.querySelector('.carousel-item.active img').src;
            changeMainImage(selectedImageUrl);
            addThumbnailListeners();
        });

        // Función para agregar el producto al carrito o comprarlo ahora mediante AJAX
        function addProducto(id, cantidad, token, comprarAhora = false) {
            const url = 'clases/carrito.php';
            const formData = new FormData();
            formData.append('id', id);
            formData.append('cantidad', cantidad);
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
                        if (comprarAhora) {
                            // Mostrar el modal de carga
                            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                            loadingModal.show();

                            // Redirigir a la página de checkout después de un pequeño retraso
                            setTimeout(() => {
                                window.location.href = 'checkout.php';
                            }, 2000); // Puedes ajustar el tiempo de espera según sea necesario
                        } else {
                            // Mostrar el toast de éxito centrado
                            const toastElement = document.getElementById('toastAddToCart');
                            const toast = new bootstrap.Toast(toastElement, {
                                autohide: true,
                                delay: 3000
                            });
                            toast.show();
                        }
                    } else {
                        alert("No hay suficientes productos en el stock");
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Event listener para el botón "Agregar al carrito"
        document.getElementById("btnAgregar").addEventListener("click", function() {
            this.classList.add("btn-success");
            setTimeout(() => {
                this.classList.remove("btn-success");
            }, 500);

            const inputCantidad = document.getElementById("cantidad").value;
            addProducto(<?php echo $id; ?>, inputCantidad, '<?php echo $token_tmp ?>');
        });

        // Event listener para el botón "Comprar ahora"
        document.getElementById("btnCompraAhora").addEventListener("click", function() {
            const inputCantidad = document.getElementById("cantidad").value;
            addProducto(<?php echo $id; ?>, inputCantidad, '<?php echo $token_tmp ?>', true);
        });

        // Función para desplazarse suavemente hacia arriba
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Mostrar u ocultar el botón de desplazamiento según la posición del scroll
        window.onscroll = () => {
            document.getElementById("btnScrollToTop").style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? "block" : "none";
        };

        // Espera 2 segundos y luego oculta el mensaje de éxito
        setTimeout(function() {
            document.getElementById('successMessage').classList.add('hide-message');
        }, 2000);
    </script>

    <?php include 'layout/footer.php'; ?>
</body>
</html>
