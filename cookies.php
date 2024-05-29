<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies - MobiStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon/favicon5.png">
    <style>
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

        h3 {
            color: #285693;
        }

    </style>
</head>

<body>

    <?php include 'layout/menu1.php'; ?>

    <main style="background-color: #f0f5ff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1 class="mt-4">Política de Cookies</h1>
                    <p class="lead">En esta sección, explicamos cómo utilizamos las cookies en nuestro sitio web y cómo puedes gestionarlas.</p>
                    <h3>¿Qué son las cookies?</h3>
                    <p>Las cookies son pequeños archivos de texto que se almacenan en tu navegador cuando visitas un sitio web. Estos archivos contienen información sobre tu actividad en el sitio y se utilizan para mejorar tu experiencia de navegación.</p>
                    <h3>¿Cómo utilizamos las cookies?</h3>
                    <p>Utilizamos cookies para diferentes propósitos, incluyendo:</p>
                    <ul>
                        <li>Analizar el tráfico del sitio para comprender cómo los visitantes interactúan con él.</li>
                        <li>Personalizar el contenido y los anuncios para adaptarlos a tus intereses.</li>
                        <li>Mejorar la funcionalidad del sitio, como recordar tus preferencias de idioma o guardar los productos en tu carrito de compras.</li>
                    </ul>
                    <h3>¿Cómo puedes gestionar las cookies?</h3>
                    <p>Tienes el control total sobre las cookies que se utilizan en nuestro sitio web. Puedes gestionar tus preferencias de cookies de las siguientes maneras:</p>
                    <ul>
                        <li>A través de la configuración de tu navegador: Puedes configurar tu navegador para que rechace todas las cookies, acepte solo ciertas cookies o te avise cada vez que se envíe una cookie a tu dispositivo.</li>
                        <li>Utilizando nuestras herramientas de gestión de cookies: Proporcionamos herramientas en nuestro sitio web para que puedas aceptar o rechazar cookies según tus preferencias.</li>
                    </ul>
                    <h3>¿Qué tipos de cookies utilizamos?</h3>
                    <p>En nuestro sitio web utilizamos los siguientes tipos de cookies:</p>
                    <ul>
                        <li>Cookies esenciales: Son necesarias para el funcionamiento básico del sitio y no pueden desactivarse en nuestros sistemas.</li>
                        <li>Cookies de rendimiento: Nos permiten contar las visitas y fuentes de tráfico para poder medir y mejorar el rendimiento del sitio.</li>
                        <li>Cookies de funcionalidad: Nos permiten recordar tus preferencias y personalizar el contenido para ti.</li>
                        <li>Cookies de marketing: Se utilizan para rastrear la actividad de navegación de los usuarios y ofrecer anuncios personalizados según sus intereses.</li>
                    </ul>
                    <h3>¿Cuánto tiempo permanecen las cookies en tu dispositivo?</h3>
                    <p>La duración de las cookies en tu dispositivo puede variar dependiendo del tipo de cookie. Algunas cookies se eliminan cuando cierras tu navegador (cookies de sesión), mientras que otras pueden permanecer en tu dispositivo durante un período más largo (cookies persistentes).</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>