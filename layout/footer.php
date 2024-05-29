<footer class="py-4" style="background: linear-gradient(to right, #285693, #2389a1);">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 style="color: white;">Enlaces útiles</h5>
                <hr style="border-color: white; border-width: 2px; width: 50%; background-color: white;">
                <ul class="list-unstyled">
                    <li><a href="#" style="text-decoration: none; color: white;">Inicio</a></li>
                    <li><a href="http://localhost/tiendaOnline/index.php" style="text-decoration: none; color: white;">Productos</a></li>
                    <li><a href="http://localhost/tiendaOnline/acerca_de_nosotros.php" style="text-decoration: none; color: white;">Quiénes Somos</a></li>
                    <li><a href="aviso.php" style="text-decoration: none; color: white;">Aviso Legal</a></li>
                    <li><a href="#" id="cookieLink" style="color: white; text-decoration: underline;">Configurar cookies</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 style="color: white;">Redes Sociales</h5>
                <hr style="border-color: white; border-width: 2px; width: 50%; background-color: white;">
                <ul class="list-unstyled">
                    <li><a href="https://es-es.facebook.com/" target="_blank" style="text-decoration: none; color: white;"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                    <li><a href="https://twitter.com/?lang=es" target="_blank" style="text-decoration: none; color: white;"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="https://www.instagram.com/" target="_blank" style="text-decoration: none; color: white;"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-12 mb-4">
                <h5 style="color: white;">Contacto</h5>
                <hr style="border-color: white; border-width: 2px; width: 50%; background-color: white;">
                <p style="color: white;">Dirección: <a href="https://maps.app.goo.gl/FajatgctAhQPkF7RA" style="text-decoration: none; color: white;" target="_blank">Calle de la Tienda, Ciudad, País</a></p>
                <p style="color: white;">Teléfono: <a href="tel:+34965456789" style="text-decoration: none; color: white;" onclick="event.preventDefault(); window.open('tel:+34965456789', '_blank');">+34 965 456 789</a></p>
                <p style="color: white;">Email: <a href="mailto:info@tienda.com" style="text-decoration: none; color: white;">info@tienda.com</a></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <p class="mb-0" style="color: white;">&copy; <span id="currentYear"></span> MobiStore. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Ventana emergente (modal) para el aviso de cookies -->
<div id="cookieModal" class="modal fade" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cookieModalLabel" style="text-align: center;">Configuración de cookies</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="d-inline-block">Cookies requeridas</h6>
                        <input type="checkbox" id="requiredCookies" checked disabled class="ml-2">
                        <p style="font-size: 14px; color: #6c757d;">Estas cookies son necesarias para que el sitio web funcione correctamente y no se pueden desactivar. Incluyen, por ejemplo, cookies que permiten iniciar sesión.</p>
                        <hr>
                        <h6 class="d-inline-block">Cookies Analíticas</h6>
                        <input type="checkbox" id="analyticsCookies" class="ml-2">
                        <p style="font-size: 14px; color: #6c757d;">Estas cookies nos permiten analizar cómo los usuarios utilizan nuestro sitio web, lo que nos ayuda a mejorar la funcionalidad y la experiencia del usuario. Por ejemplo, nos permiten contar las visitas y fuentes de tráfico para poder medir y mejorar el rendimiento del sitio.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="d-inline-block">Cookies Funcionales</h6>
                        <input type="checkbox" id="functionalCookies" class="ml-2">
                        <p style="font-size: 14px; color: #6c757d;">Estas cookies permiten que el sitio web proporcione funcionalidades y personalización mejoradas, como recordar tus preferencias de idioma y configuración.</p>
                        <hr>
                        <h6 class="d-inline-block">Cookies de Marketing</h6>
                        <input type="checkbox" id="marketingCookies" class="ml-2">
                        <p style="font-size: 14px; color: #6c757d;">Estas cookies se utilizan para rastrear la actividad de navegación de los usuarios y ofrecer anuncios personalizados según sus intereses. Por ejemplo, pueden ser utilizadas para mostrar anuncios relevantes basados en las páginas que has visitado anteriormente o en tus hábitos de navegación.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <!-- Texto con enlace a la página de Política de Cookies -->
                <p style="font-size: 14px; color: #6c757d;">Para más información, consulta nuestra <a href="cookies.php" style="color: #285693;">Política de Cookies</a>.</p>
                <!-- Botón para guardar las elecciones -->
                <button type="button" class="btn btn-primary" id="saveCookies">GUARDAR MIS ELECCIONES</button>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript para controlar el aviso de cookies -->
<script>
    // Función para mostrar el modal al hacer clic en el enlace
    document.getElementById('cookieLink').addEventListener('click', function(event) {
        event.preventDefault();
        var myModal = new bootstrap.Modal(document.getElementById('cookieModal'));
        myModal.show();
    });

    // Al hacer clic en guardar, guardar la configuración de cookies
    document.getElementById('saveCookies').addEventListener('click', function() {
        // Guardar la configuración de cookies en localStorage
        var analyticalCookie = document.getElementById('analyticsCookies').checked;
        var functionalCookie = document.getElementById('functionalCookies').checked;
        var marketingCookie = document.getElementById('marketingCookies').checked;

        localStorage.setItem('analyticalCookie', analyticalCookie);
        localStorage.setItem('functionalCookie', functionalCookie);
        localStorage.setItem('marketingCookie', marketingCookie);

        // Cerrar el modal
        var myModal = document.getElementById('cookieModal');
        var modalInstance = bootstrap.Modal.getInstance(myModal);
        modalInstance.hide();
    });

    // Al cargar la página, cargar la configuración de cookies desde localStorage si existe
    window.addEventListener('load', function() {
        var analyticalCookie = localStorage.getItem('analyticalCookie');
        var functionalCookie = localStorage.getItem('functionalCookie');
        var marketingCookie = localStorage.getItem('marketingCookie');

        // Si las cookies existen en localStorage, actualizar los checkboxes
        if (analyticalCookie !== null) {
            document.getElementById('analyticsCookies').checked = JSON.parse(analyticalCookie);
        }
        if (functionalCookie !== null) {
            document.getElementById('functionalCookies').checked = JSON.parse(functionalCookie);
        }
        if (marketingCookie !== null) {
            document.getElementById('marketingCookies').checked = JSON.parse(marketingCookie);
        }
    });

    // Mostrar el año actual en el footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>


