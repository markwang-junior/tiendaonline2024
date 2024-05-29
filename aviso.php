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
    <title>Aviso Legal - MobiStore</title>
    <!-- Se incluyen los archivos CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

<body class="aviso-legal" style="background-color: #f0f5ff;">

    <?php include 'layout/menu1.php'; // Se incluye el menú 
    ?>

    <div class="container">
        <!-- Encabezado y contenido del aviso legal -->
        <h1 style="margin-top: 20px;">Aviso Legal</h1>
        <p>En cumplimiento con el deber de información recogido en artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y del Comercio Electrónico, a continuación se reflejan los siguientes datos: el titular de dominio web es Tienda Online de Móviles, con domicilio a estos efectos en Calle de la Tienda, Ciudad, País, con número de C.I.F. número de identificación fiscal. Correo electrónico de contacto: info@tienda.com.</p>

        <!-- Sección Usuarios -->
        <h2>Usuarios</h2>
        <p>El acceso y/o uso de este portal de Tienda Online de Móviles atribuye la condición de usuario, que acepta, desde dicho acceso y/o uso, las Condiciones Generales de Uso aquí reflejadas. Las citadas Condiciones serán de aplicación independientemente de las Condiciones Generales de Contratación que en su caso resulten de obligado cumplimiento.</p>

        <!-- Sección Uso del portal -->
        <h2>Uso del portal</h2>
        <p>El sitio web de Tienda Online de Móviles proporciona el acceso a multitud de informaciones, servicios, programas o datos (en adelante, "los contenidos") en Internet pertenecientes a Tienda Online de Móviles o a sus licenciantes a los que el usuario pueda tener acceso. El usuario asume la responsabilidad del uso del portal. Dicha responsabilidad se extiende al registro que fuese necesario para acceder a determinados servicios o contenidos.</p>

        <!-- Sección Propiedad intelectual e industrial -->
        <h2>Propiedad intelectual e industrial</h2>
        <p>Tienda Online de Móviles por sí o como cesionaria, es titular de todos los derechos de propiedad intelectual e industrial de su página web, así como de los elementos contenidos en la misma (a título enunciativo, imágenes, sonido, audio, vídeo, software o textos; marcas o logotipos, combinaciones de colores, estructura y diseño, selección de materiales usados, programas de ordenador necesarios para su funcionamiento, acceso y uso, etc.), titularidad de Tienda Online de Móviles. Serán, por consiguiente, obras protegidas como propiedad intelectual por el ordenamiento jurídico español, siéndoles aplicables tanto la normativa española y comunitaria en este campo, como los tratados internacionales relativos a la materia y suscritos por España.</p>

        <!-- Sección Exclusión de garantías y responsabilidad -->
        <h2>Exclusión de garantías y responsabilidad</h2>
        <p>El contenido del sitio web de Tienda Online de Móviles tiene carácter meramente informativo y puede no reflejar el estado actual de la legislación o la jurisprudencia. Tienda Online de Móviles se reserva el derecho a modificar, actualizar o eliminar cualquier contenido sin previo aviso, así como a limitar o denegar el acceso a dicho contenido. Tienda Online de Móviles no se responsabiliza del contenido de las páginas web a las que enlaza fuera de su sitio.</p>

        <!-- Sección Legislación aplicable y jurisdicción -->
        <h2>Legislación aplicable y jurisdicción</h2>
        <p>La relación entre Tienda Online de Móviles y el usuario se regirá por la normativa española vigente y cualquier controversia se someterá a los Juzgados y tribunales de la ciudad de [Nombre de la ciudad], salvo que la ley disponga otra cosa.</p>
    </div>

    <?php include 'layout/footer.php'; // Se incluye el pie de página 
    ?>

</body>