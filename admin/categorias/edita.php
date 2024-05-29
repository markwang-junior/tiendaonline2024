<?php 

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../header.php';

if(!isset($_SESSION['user_type'])) {
    header ('Location: ../index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin') {
    header ('Location: ../../index.php');
    exit;
}


$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

$sql = $con->prepare("SELECT id, nombre FROM categorias WHERE id = ? LIMIT 1");
$sql->execute([$id]);
$categoria = $sql->fetch(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Edita Categorías</h2>

        <form action="actualiza.php" method="post" autocomplete="off">

            <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>" />

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $categoria['nombre']; ?>" required autofocus/>
            </div>
            
            <button type="submit" class="btn btn-primary"> Guarda </button>
            
        </form>

    </div>
</main>


<?php require_once '../footer.php'; ?>