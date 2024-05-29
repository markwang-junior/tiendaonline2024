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

$sql ="SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias =$resultado->fetchAll(PDO::FETCH_ASSOC);


?>

<style>
    .ck-editor__editable[role="textbox"]{
        min-height: 200px;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-2">Nuevo producto</h2>

        <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus/>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion" id="editor"></textarea>
            </div>

            <div class="row mb-2">
    <div class="col">
        <label for="imagen_principal" class="form-label">Imagen principal</label>
        <input type="file" class="form-control" name="imagen_principal" id="imagen_principal" accept="image/jpeg, image/jpg, image/png" required />
    </div>
    <div class="col">
        <label for="otras_imagenes" class="form-label">Otras Imágenes</label>
        <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes" accept="image/jpeg, image/jpg, image/png" multiple />
    </div>
</div>

            

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="precio" id="precio" required />
                </div>

                <div class="col mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" name="descuento" id="descuento" required />
                </div>

                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" required />
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" name="categoria" id="categoria" required>
                            <option value>Selecionar</option>
                            <<?php foreach($categorias as $categoria){ ?>
                                  <option value="<?php echo $categoria['id']; ?> " ><?php echo $categoria['nombre']; ?> </option>
                                <?php } ?>
                        </select>   
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion1" class="form-label">Descripcion extra</label>
                <textarea class="form-control" name="descripcion1" id="editor1"></textarea> <!-- Corregido el cierre de la etiqueta textarea -->
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
</script>


<?php require_once '../footer.php'; ?>