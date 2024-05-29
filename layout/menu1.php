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
                <?php } else  { ?>
                    <a href="login.php" class="btn btn-success btn-sm">
                        <i class="fas fa-user me-1"></i>Iniciar sesión
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
