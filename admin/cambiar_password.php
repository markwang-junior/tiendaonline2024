<!-- reset_password.php -->

<?php 

    require_once 'config/database.php';
    require_once 'config/config.php';
    require_once 'clases/adminFunciones.php';
    
    if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
        header ('Location: ../index.php');
        exit;
    }

    $user_id = $_GET['id'] ?? $_POST['id'] ?? '';


    if($user_id == '' || $user_id != $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }

    $db = new Database();
    $con = $db->conectar();

    $errors = [];

    if (!empty($_POST)) {
        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        if (esNulo([$user_id, $password, $repassword])) {
            $errors[] = "Debes llenar todos los campos";
        }

        if (!validaPassword($password, $repassword)) {
            $errors[] = "Las contraseñas no coinciden";
        }

        if(empty($errors)){
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            if(actualizaPasswordAdmin($user_id, $pass_hash, $con)){
                $errors[] = "Contraseña modificada.";
            } else {
                $errors[] = "No se pudo actualizar la contraseña. Inténtalo nuevamente";
            }
        }
    }

    $sql = "SELECT id, usuario FROM admin WHERE id = ?";
    $sql = $con->prepare($sql);
    $sql->execute([$user_id]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    require 'header.php';
    
?>

<main class="form-login m-auto pt-4">
   <h3>Cambiar contraseña</h3> <!-- Se cerró la etiqueta h3 -->

   <?php mostrarMensajes($errors);?>

   <form action="cambiar_password.php" method="post" class="row g-3" autocomplete="off">

   <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

   <div class="form-floating ">
        <input type="text" class="form-control" id="password"  value="<?php echo $usuario['usuario']; ?>" disabled>
        <label for="usuario">Usuario</label>
    </div>

    <div class="form-floating ">
        <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required >
        <label for="email">Nueva Contraseña</label>
    </div>


    <div class="form-floating ">
        <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Confirmar contraseña" required >
        <label for="email">Confirmar contraseña</label>
    </div>

    <div class="col-12 d-grid gap-3">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
</main>

<?php require 'footer.php';?>
