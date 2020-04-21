<?php
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$sql = "select id, descripcion, catalogo from catalogo_valor where catalogo=1;";
$resultado = $mysqli->query($sql);
$filas = $resultado->num_rows;
?>


<thead class="box box-primary " >
<!--p class="titBoveda centrado">LISTA DE ROLES</p-->
<tr>
    <!--<th style="width: 20%;">Id</th>--> 
    <td style="width: 60%; padding-left: 50px;">Nombre</td>                  
    <td style="width: 10%;">Acciones</td>
</tr>
</thead>
<tbody>         

    <?php
    while ($fila = $resultado->fetch_assoc()) {
        ?>
        <tr>                                 
            <!--<td><?php echo $fila['id']; ?></td>--> 
            <td style="padding-left: 50px;"><?php echo $fila['descripcion']; ?></td>                                 
            <td>
                <button type="button" title="Editar" class="btn no-padding btn-link" onclick="edita_rol(<?php echo $fila['id']; ?>, '<?php echo $fila['descripcion']; ?>')"><span class="fa fa-pencil"></span></button>
                &nbsp;&nbsp;&nbsp;
                <button type="button" title="Funciones" class="btn btn-link no-padding" onclick="funciones_rol(<?php echo $fila['id']; ?>, '<?php echo $fila['descripcion'] ?>')"><span class="glyphicon glyphicon-wrench"></span></button>
            </td>
        </tr>
    <?php 
    }
    ?>

<!--    <tr>
        <td colspan = "3">Num. de registros: <?php echo $filas; ?></td>
    </tr>-->
</tbody>