<?php 

require_once '../config/database.php';
require_once '../config/config.php';
require_once '../header.php';

if(!isset($_SESSION['user_type'])) {
    header ('Location: index.php');
    exit;
}

if($_SESSION['user_type'] != 'admin') {
    header ('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];  

$sql = $con->prepare("SELECT id, nombre, descripcion, descripcion1, precio, stock, descuento, id_categoria FROM productos WHERE id= ? AND activo = 1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

$sql ="SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias =$resultado->fetchAll(PDO::FETCH_ASSOC);

$rutaImagenes = '../../images/productos/'. $id .'/';
$imagenPrincipal = $rutaImagenes . 'principal.jpg';

$imagenes = [];
$dirInit = dir($rutaImagenes);

while(($archivo = $dirInit->read())!== false) {
    if($archivo != 'principal.jpg' && (strpos($archivo, 'jpg')|| strpos($archivo, 'jpeg'))) {
        $image = $rutaImagenes . $archivo;
        $imagenes[] = $image;
    }
}
$dirInit->close();

?>

<style>
    .ck-editor__editable[role="textbox"]{
        min-height: 200px;
    }
    .scroll-to-top,
    .scroll-to-bottom {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    font-size: 20px;
    z-index: 1000;
    text-decoration: none;
}

    .scroll-to-bottom {
    bottom: 70px;
}

    .scroll-to-top:hover,
    .scroll-to-bottom:hover {
    background-color: #0056b3;
}

</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-2">Modificar producto</h2>

         <!-- Flechas de desplazamiento -->
         <a href="#" id="scroll-to-top" class="scroll-to-top"><i class="fas fa-arrow-up"></i></a>
        <a href="#" id="scroll-to-bottom" class="scroll-to-bottom"><i class="fas fa-arrow-down"></i></a>


        <form action="actualiza.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $producto ['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>" required autofocus/>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion" id="editor"><?php echo $producto['descripcion']; ?></textarea> <!-- Corregido el cierre de la etiqueta textarea -->
            </div>

            <div class="row mb-2">
    <div class="col">
        <label for="imagen_principal" class="form-label">Imagen principal</label>
        <input type="file" class="form-control" name="imagen_principal" id="imagen_principal" accept="image/jpeg, image/jpg, image/png"  />
    </div>
    <div class="col">
        <label for="otras_imagenes" class="form-label">Otras Imágenes</label>
        <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes" accept="image/jpeg, image/jpg, image/png" multiple />
    </div>
</div>

<div class="row mb-2">
    <div class="col-12 col-md-6">
        <?php if(file_exists($imagenPrincipal)) { ?>
            <img src="<?php echo $imagenPrincipal. '?id=' . time(); ?>" class="img-thumbnail my-3" alt="Imagen Principal"><br>
            <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagenPrincipal; ?>')">Eliminar</button>
        <?php } ?>
    </div>

    <div class="col-12 col-md-6">
        <div class="row">
            <?php foreach($imagenes as $imagen) { ?>
                <div class="col-4">
                    <img src="<?php echo $imagen . '?id=' . time(); ?>" class="img-thumbnail my-3" alt="Otra Imagen"><br>
                    <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagen; ?>')">Eliminar</button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

            

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="precio" id="precio" value="<?php echo $producto['precio']; ?>" required />
                </div>

                <div class="col mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" name="descuento" id="descuento" value="<?php echo $producto['descuento']; ?>" required />
                </div>

                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $producto['stock']; ?>" required />
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" name="categoria" id="categoria" required>
                        <option value="">Seleccionar</option> <!-- Agregado el atributo value vacío -->
                        <?php foreach($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php if ($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?>>
                                <?php echo $categoria['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion1" class="form-label">Descripcion extra</label>
                <textarea class="form-control" name="descripcion1" id="editor1"><?php echo $producto['descripcion1']; ?></textarea> <!-- Corregido el cierre de la etiqueta textarea -->
            </div>
            
            <button type="submit" class="btn btn-primary"> Guarda </button>
            
        </form>

    </div>
</main>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
        ClassicEditor
        .create( document.querySelector( '#editor1' ) )
        .catch( error => {
            console.error( error );
        } );

    function eliminaImagen(urlImagen){
        let url = 'eliminar_imagen.php';
        let formData = new FormData();
        formData.append('urlImagen', urlImagen);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => {
            if(response.ok) {
                location.reload();
            } 
        });
    }

    document.getElementById('scroll-to-top').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    document.getElementById('scroll-to-bottom').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });
    });
</script>


<?php require_once '../footer.php'; ?>
