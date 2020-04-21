<?php
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
$condiciones = "";
if (isset($_POST['sltHospital']) && !empty($_POST["sltHospital"])) {
    $condiciones = $condiciones . " AND uh.hospital = " . $_POST["sltHospital"];
}
if (isset($_POST['sltRol']) && !empty($_POST["sltRol"])) {
    $condiciones = $condiciones . " AND u.rol = " . $_POST["sltRol"];
}
if (isset($_POST['txtVarios']) && !empty($_POST["txtVarios"])) {
    $condiciones = $condiciones . " AND (u.login LIKE '%" . $_POST["txtVarios"] . "%' || u.nombre LIKE '%" . $_POST["txtVarios"] . "%' || u.email LIKE '%" . $_POST["txtVarios"] . "%')";
}


$sql = "select DISTINCT(u.id_usuario), u.nombre, u.login, u.email, u.rol, u.ind_activo, u.fecha_creacion, u.ultima_visita, cv.descripcion, u.puesto,u.ind_notificaciones from usuarios u left join catalogo_valor cv on u.rol=cv.id and cv.catalogo=1 left join usuario_hospital uh on u.id_usuario=uh.usuario where true " . $condiciones . " and desactivar='0'  order by rol, puesto asc";

//echo $sql;
$resultado = $mysqli->query($sql);
?>

<table id="tblUsuarios" name="tblUsuarios" class="table-s table table-hover table-responsive tbl-det-tickets">
    <thead class="box box-primary">
        <tr> 
            <td></td>
            <td></td>
            <td>Nombre de usuario</td>
            <td>Nombre completo</td>
            <td>Puesto</td>
            <td>Correo electrónico</td>
            <td>Rol</td>
            <td style='text-align: center;'>Activo</td>
            <td>Ultima visita</td>

        </tr>
    </thead>

    <tbody>
        <?php
        while ($row = $resultado->fetch_assoc()) {
            $color = '';
            $edit = $row['id_usuario'] . ', "' . $row['nombre'] . '", "' . $row['login'] . '", "' . $row['email'] . '", ' . $row['rol'] . ',' . $row['ind_activo'] . ', "' . $row['puesto'] . '", ' . $row['ind_notificaciones'];
            if ($row['rol'] == '8') {
                $color = '#d3f6d8';
            } else
            if ($row['rol'] == '2') {
                $color = '#d5e1f3';
            } else
            if ($row['rol'] == '3') {
                $color = '#f9e8c9';
            } else {
                $color = '#eae9e9';
            }
            echo "<tr>";
            echo "<td style='text-align:center; padding-right:25px;'>";
            echo "<button type='button' title='Eliminar' class='btn btn-link no-padding' onclick='eliminarUsuario($edit)'> <span class='fa fa-user-times'></span> </button>";
            echo "</td>";
            echo "<td style='text-align:center; padding-right:25px;'>";
            echo "<button type='button' title='Editar' class='btn btn-link no-padding' onclick='editaUsuario($edit)'> <span class='fa fa-pencil-square-o'></span> </button>";
            echo "</td>";
            echo "<td style='background: $color'>" . $row['login'] . "</td>";

            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['puesto'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['descripcion'] . "</td>";
            echo "<td style='text-align: center; padding-top:4px;'>" . (intval($row['ind_activo']) == 1 ? "<img src='resources/images/ok.png' style='height:13px;'>" : "<img src='resources/images/no-ok.png' style='height:13px;'>") . "</td>";

            echo "<td>" . ($row['ultima_visita'] == '' ? '' : date_format(date_create($row['ultima_visita']), "d/m/y H:i")) . "</td>";

            echo "<tr>";
        }
        ?>
    </tbody>

</table>
