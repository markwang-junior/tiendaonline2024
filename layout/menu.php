<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #suggestions {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #fff;
            display: none;
        }
        .suggestion-item {
            padding: 0.5rem;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(to right, #285693, #2389a1);">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <i class="fas fa-mobile-alt me-1" style="font-size: 32px; color: #FFC107;"></i>
                <strong style="color: white; font-family: sans-serif; font-size: 32px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                    <span style="color: #FFC107;">M</span>o<span style="color: #FFC107;">b</span>i<span style="color: #FFC107;">S</span>tore
                </strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active" style="color: white; font-weight: bold; text-transform: uppercase; font-size: 16px;">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a href="acerca_de_nosotros.php" class="nav-link active" style="color: white; font-weight: bold; text-transform: uppercase; font-size: 16px;">Quiénes Somos</a>
                    </li>
                </ul>

                <form action="index.php" method="get" autocomplete="off" class="mt-2 mt-lg-0 position-relative">
                    <div class="input-group pe-2" style="width: 400px;"> <!-- Cambiado el ancho a 400px -->
                        <input type="text" name="q" id="q" class="form-control form-control-sm" placeholder="Buscar..." aria-describedby="icon-buscar">
                        <button type="submit" class="btn btn-outline-info btn-sm" id="icon-buscar">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="suggestions" class="list-group"></div>
                </form>

                <div class="d-flex align-items-center mt-2 mt-lg-0">
                    <a href="checkout.php" class="btn btn-primary me-2 btn-sm">
                        <i class="fas fa-shopping-cart me-1"></i>Carrito
                        <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>

                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btn_session">
                                <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user"></i> Mi perfil</a></li>
                                <li><a class="dropdown-item" href="compras.php"><i class="fas fa-shopping-bag"></i> Mis compras</a></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-success btn-sm">
                            <i class="fas fa-user me-1"></i>Iniciar sesión
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('q');
        const suggestionsBox = document.getElementById('suggestions');

        searchInput.addEventListener('input', function() {
            const query = this.value;

            if (query.length > 0) {
                fetch(`includes/../config/search.php?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('suggestion-item');
                                suggestionItem.textContent = product.nombre;
                                suggestionItem.addEventListener('click', function() {
                                    window.location.href = `index.php?q=${product.nombre}`;
                                });
                                suggestionsBox.appendChild(suggestionItem);
                            });
                            suggestionsBox.style.display = 'block';
                        } else {
                            suggestionsBox.style.display = 'none';
                        }
                        // Guardar la consulta en el almacenamiento local
                        saveQuery(query);
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                suggestionsBox.innerHTML = '';
                suggestionsBox.style.display = 'none';
            }
        });

        // Cargar las consultas anteriores al cargar la página
        loadPreviousQueries();

        // Cerrar las sugerencias cuando se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.style.display = 'none';
            }
        });

        // Función para guardar la consulta en el almacenamiento local
        function saveQuery(query) {
            let queries = localStorage.getItem('searchQueries');
            queries = queries ? JSON.parse(queries) : [];
            if (!queries.includes(query)) {
                queries.push(query);
                localStorage.setItem('searchQueries', JSON.stringify(queries));
            }
        }

        // Función para cargar las consultas anteriores desde el almacenamiento local
        function loadPreviousQueries() {
            let queries = localStorage.getItem('searchQueries');
            queries = queries ? JSON.parse(queries) : [];
            queries.forEach(query => {
                const suggestionItem = document.createElement('div');
                suggestionItem.classList.add('suggestion-item');
                suggestionItem.textContent = query;
                suggestionItem.addEventListener('click', function() {
                    searchInput.value = query;
                    suggestionsBox.innerHTML = '';
                });
                suggestionsBox.appendChild(suggestionItem);
            });
        }
    });
    </script>
</body>
</html>
