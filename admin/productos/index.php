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

// Buscador
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Obtener el término de búsqueda
$search_condition = $search ? " AND nombre LIKE '%$search%'" : ''; // Agregar condición de búsqueda si se proporciona un término

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual, por defecto es la primera página
$records_per_page = 10; // Número de productos por página
$offset = ($page - 1) * $records_per_page; // Calcular el desplazamiento

$sql ="SELECT id, nombre, descripcion, precio, descuento, stock, id_categoria FROM productos WHERE activo = 1 $search_condition LIMIT $offset, $records_per_page";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-2">Productos</h2>
        <a href="nuevo.php" class="btn btn-primary">Agregar</a>

        <!-- Buscador -->
        <form class="mt-3 mb-3" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar producto..." name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="cursor: pointer;">Nombre</th>
                        <th scope="col" style="cursor: pointer;">Precio</th>
                        <th scope="col" style="cursor: pointer;">Stock</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $producto){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo '€' . $producto['precio']; ?></td> <!-- Agrega el símbolo del euro aquí -->
                            <td <?php echo ($producto['stock'] == 0) ? 'style="color: red;"' : ''; ?>><?php echo $producto['stock']; ?></td>

                            <td>
                                <a href="edita.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $producto['id'];?>">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Controles de paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1) : ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Anterior</a></li>
                <?php endif; ?>
                <?php if (count($productos) == $records_per_page) : ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Siguiente</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</main>

<!-- Modal Body -->
<div class="modal fade" id="modalElimina" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">¿Desea eliminar el registro?</div>
            <div class="modal-footer">
                <form action="elimina.php" method="post">
                    <input type="hidden" name="id" id="eliminarId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    let eliminaModal = document.getElementById('modalElimina');
    eliminaModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let modalIdInput = eliminaModal.querySelector('#eliminarId');
        modalIdInput.value = id;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        let sortOrder = {}; // Objeto para mantener el estado del orden de cada columna

        const getPriceValue = (priceString) => {
            // Eliminar el símbolo del euro y cualquier espacio en blanco
            return parseFloat(priceString.replace(/[^\d.]/g, ''));
        };

        const sortTable = (index, type) => {
            rows.sort((a, b) => {
                let valueA = a.children[index].textContent.trim();
                let valueB = b.children[index].textContent.trim();
                if (type === 'number') {
                    // Convertir los precios en números para comparar
                    return getPriceValue(valueA) - getPriceValue(valueB);
                } else {
                    return valueA.localeCompare(valueB);
                }
            });

            // Revertir el orden si ya está ordenado de forma ascendente
            if (sortOrder[index] === 'asc') {
                rows.reverse();
                sortOrder[index] = 'desc'; // Cambiar a orden descendente
            } else {
                sortOrder[index] = 'asc'; // Mantener el orden ascendente
            }

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        };

        document.querySelectorAll('th').forEach((header, index) => {
            header.addEventListener('click', () => {
                if (index === 1) {
                    sortTable(index, 'number'); // Ordenar por Precio
                } else if (index === 2) {
                    sortTable(index, 'number'); // Ordenar por Stock
                } else if (index === 0) {
                    sortTable(index, 'string'); // Ordenar por Nombre
                }
            });
        });
    });
</script>

<?php require_once '../footer.php'; ?>
