<?php
// Se incluyen los archivos necesarios
require_once 'config/config.php'; // Archivo de configuración
require_once 'config/database.php'; // Conexión a la base de datos
require_once 'clases/clienteFunciones.php'; // Funciones relacionadas con los clientes
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - MobiStore</title>
    <!-- Se incluyen las hojas de estilo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css1/estilos.css" rel="stylesheet"> <!-- Estilos personalizados -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
        /* Estilos adicionales para hacer el contenido más responsive */
        img {
            max-width: 100%;
            height: auto;
        }

        h2 {
            margin-top: 30px;
            color: #285693;
        }

        .section-content {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        /* Estilos personalizados */
        body {
            background-color: #f8f9fa;
        }

        h1 {
            color: #285693;
            font-weight: bold;
            margin-top: 0;
            /* Eliminar margen superior del h1 */
        }

        p {
            color: #6c757d;
        }
    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; // Se incluye el menú 
    ?>

    <main style="background-color: #f0f5ff;">
        <section class="container mt-3">
            <h1 class="mb-3 text-center">Quiénes Somos</h1>

            <div class="row section-content">
                <div class="col-md-6 mb-4">
                    <img src="images/imagenTienda.jpeg" class="img-fluid" alt="Acerca de nosotros">
                </div>
                <div class="col-md-6">
                    <p>
                        Bienvenido a MobiStore, tu destino para encontrar los últimos y mejores teléfonos móviles, accesorios y dispositivos electrónicos. En MobiStore, nos apasiona brindar a nuestros clientes una experiencia de compra excepcional y acceso a la tecnología más avanzada.
                    </p>
                    <h2>Nuestra Misión</h2>
                    <p>
                        Nuestra misión es ofrecer una amplia selección de productos de alta calidad, desde los últimos modelos de teléfonos inteligentes hasta los accesorios más innovadores, junto con un servicio al cliente excepcional. Estamos comprometidos a proporcionar a nuestros clientes la mejor experiencia de compra en línea, brindando confianza, comodidad y seguridad en cada transacción.Ya sea que estés buscando el último teléfono inteligente, un accesorio elegante o simplemente quieras explorar las últimas innovaciones tecnológicas, ¡estamos aquí para ayudarte! Únete a la comunidad de MobiStore y descubre un mundo de posibilidades tecnológicas.Ya sea que estés buscando el último teléfono inteligente, un accesorio elegante o simplemente quieras explorar las últimas innovaciones tecnológicas, ¡estamos aquí para ayudarte! Únete a la comunidad de MobiStore y descubre un mundo de posibilidades tecnológicas.
                    </p>
                </div>
            </div>

            <div class="section-content">
                <h2>Nuestra Visión</h2>
                <p>
                    En MobiStore, buscamos convertirnos en el principal destino en línea para todas las necesidades relacionadas con teléfonos móviles y dispositivos electrónicos. Nos esforzamos por mantenernos a la vanguardia de la tecnología y la innovación, ofreciendo productos de última generación y manteniendo nuestro compromiso con la excelencia en el servicio al cliente.

                </p>
            </div>

            <div class="section-content">
                <h2>Por qué elegirnos</h2>
                <ul>
                    <li>Amplia selección de productos de las mejores marcas.</li>
                    <li>Garantía de calidad en todos nuestros productos.</li>
                    <li>Envío rápido y seguro a cualquier parte del mundo.</li>
                    <li>Equipo de atención al cliente dedicado y amigable.</li>
                    <li>Ofertas y promociones exclusivas para nuestros clientes.</li>
                </ul>
            </div>

            <div class="section-content">
                <h2>Únete a nosotros</h2>
                <p>
                    Ya sea que estés buscando el último teléfono inteligente, un accesorio elegante o simplemente quieras explorar las últimas innovaciones tecnológicas, ¡estamos aquí para ayudarte! Únete a la comunidad de MobiStore y descubre un mundo de posibilidades tecnológicas.
                </p>
            </div>
        </section>
    </main>

    <?php include 'layout/footer.php'; // Se incluye el pie de página 
    ?>

    <!-- Se incluye el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>