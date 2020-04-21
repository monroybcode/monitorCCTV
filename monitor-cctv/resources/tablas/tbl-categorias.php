<?php
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$sql = "select * from categoria where tipo_categoria=1;";
$resultado = $mysqli->query($sql);
$filas = $resultado->num_rows;
?>


<thead class="box box-primary " >
<!--p class="titBoveda centrado">LISTA DE ROLES</p-->
<tr>
    <!--<th style="width: 20%;">Id</th>--> 
    <td style="width: 60%; padding-left: 50px;">Nombre</td> 
    <td style="text-align:center;">Activo</td>                 
    <td style="width: 10%;">Accion</td>
</tr>
</thead>
<tbody >         

    <?php
    while ($fila = $resultado->fetch_assoc()) {
        ?>
        <tr>                                 
            <!--<td><?php echo $fila['id']; ?></td>--> 
            <td style="padding-left: 50px;"><?php echo $fila['nombre']; ?></td>    
             <?php echo "<td style='text-align: center;'>" . (intval($fila['esta_activo']) == 1 ? "<img src='resources/images/ok.png' style='height:13px;'>" : "<img src='resources/images/no-ok.png' style='height:13px;'>") . "</td>";?>                             
            <td>
                <button type="button" title="Editar" class="btn no-padding btn-link" onclick="editar_categoria(<?php echo $fila['id']; ?>);"><span class="fa fa-pencil"></span></button>
                &nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    <?php 
    }
    ?>
</tbody>