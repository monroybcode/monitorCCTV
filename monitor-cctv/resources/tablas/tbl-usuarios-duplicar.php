<?php
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
$condiciones="";
if(isset($_POST['sltHospital']) && !empty($_POST["sltHospital"])){
    $condiciones = $condiciones . " AND uh.hospital = ".$_POST["sltHospital"];
}
if(isset($_POST['sltRol']) && !empty($_POST["sltRol"])){
    $condiciones = $condiciones . " AND u.rol = ".$_POST["sltRol"];
}
if(isset($_POST['txtVarios']) && !empty($_POST["txtVarios"])){
    $condiciones = $condiciones . " AND (u.login LIKE '%".$_POST["txtVarios"]."%' || u.nombre LIKE '%".$_POST["txtVarios"]."%' || u.email LIKE '%".$_POST["txtVarios"]."%')";
}


$sql = "select DISTINCT(u.id_usuario), u.nombre, u.login, u.email, u.rol, u.ind_activo, u.fecha_creacion, u.ultima_visita, cv.descripcion, u.puesto, u.ind_notificaciones from usuarios u inner join catalogo_valor cv on u.rol=cv.id and cv.catalogo=1 inner join usuario_hospital uh on u.id_usuario=uh.usuario where true ".$condiciones;
$resultado = $mysqli->query($sql);
?>

<table id="tblUsuarios" name="tblUsuarios" class="table-s table table-hover table-responsive tbl-det-tickets">
    <thead class="box box-primary">
        <tr> 
            <td>Nombre de usuario</td>
            <td>Nombre completo</td>
            <td>Correo electrónico</td>
            <td>Rol</td>

            <td></td>
        </tr>
    </thead>

    <tbody>
        <?php
        while ($row = $resultado->fetch_assoc()) {
            $edit = $row['id_usuario'] . ', "' . $row['nombre'] . '", "' . $row['login'] . '", "' . $row['email'] . '", ' . $row['rol'] . ',' . $row['ind_activo'] . ', "' . $row['puesto'] . '",' . $row['ind_notificaciones'];

            echo "<tr>";
            echo "<td style='background: #eae9e9;'>" . $row['login'] . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['descripcion'] . "</td>";

            echo "<td style='text-align:center; padding-right:25px;'>";
            echo "<button type='button' title='Duplicar' class='btn btn-link no-padding' onclick='duplicarUsuarioP2($edit)'> <span class='glyphicon glyphicon-duplicate'></span> </button>";
            echo "</td>";


            echo "<tr>";
        }
        ?>
    </tbody>

</table>
