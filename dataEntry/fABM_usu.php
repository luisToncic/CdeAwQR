<?php
//include '../includes/header.php';
include '../includes/db.php';

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'directivo') {
    echo "<div class='alert alert-danger'>Acceso denegado.</div>";
    exit();
}
?>
<div class="container mt-5">
    <h2 class="primary-text">TABLA USUARIOS</h2>
    <table id="usuariosTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Legajo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM usuarios WHERE deleted = 0";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["usuario"] . "</td>";
                echo "<td>" . $row["contraseña"] . "</td>";
                echo "<td>" . $row["rol"] . "</td>";
                echo "<td>" . $row["legajo"] . "</td>";
                echo "<td>
                    <div class='button-container'>    
                        <a href='update.php?type=usuarios&id=" . $row["id"] . "' class='btn btn-light btn-icon btn-edit'>
                            <i class='fas fa-edit' title='Editar'></i>
                        </a>

                        <form action='bABM.php' method='POST' style='display:inline;' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='form_type' value='usuarios'>
                            <input type='hidden' name='user_id' value='" . $row["id"] . "'>
                            <input type='hidden' name='action' value='Eliminar'>
                            <button type='submit' class='btn btn-light btn-icon btn-delete'>
                                <i class='fas fa-trash-alt' title='Eliminar'></i>
                            </button>
                        </form>
                    </div>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#usuariosTable').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" }
        });
    });
    function confirmDelete() {
        return confirm("¿Seguro desea eliminar?");
    }
</script>
<?php include '../includes/footer.php'; ?>
