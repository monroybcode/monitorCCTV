
<?php
$sql = "select * from bitacora order by fecha_registro DESC";
$resultado = $mysqli->query($sql);
$filas = $resultado->num_rows;
?>


<thead class="box box-primary " >
    <!--p class="titBoveda centrado">LISTA DE ROLES</p-->
    <tr>
        <!--<th style="width: 20%;">Id</th>--> 
        <td>Ticket</td> 
        <td>Fecha Registro</td>                 
        <td>Descripcion</td>
    </tr>
</thead>
<tbody class="buscar1">         

<?php
while ($fila = $resultado->fetch_assoc()) {
    ?>
        <tr>                                 
            <!--<td><?php echo $fila['id']; ?></td>--> 
            <td style="padding-left: 50px;"><?php echo $fila['ticket']; ?></td>    
            <td style="padding-left: 50px;"><?php echo $fila['fecha_registro']; ?></td>  
            <td style="padding-left: 50px;"><?php echo $fila['descripcion']; ?></td>  

        </tr>
    <?php
}
?>
</tbody>