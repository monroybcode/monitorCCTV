
<?php
$sql = "select * from log_mail where status ='Error'  order by id_ticket DESC";
$resultado = $mysqli->query($sql);
$filas = $resultado->num_rows;
?>


<thead class="box box-primary " >
    <!--p class="titBoveda centrado">LISTA DE ROLES</p-->
    <tr>
        <!--<th style="width: 20%;">Id</th>--> 
        <td>Ticket</td> 
        <td>Estatus</td>                 
        <td>Descripcion</td>
        <td>Reenviar</td>
    </tr>
</thead>
<tbody class="buscar2">         

    <?php
    while ($fila = $resultado->fetch_assoc()) {
        ?>
        <tr>                                 
                <!--<td><?php echo $fila['id']; ?></td>--> 
            <td style="padding-left: 50px;"><?php echo $fila['id_ticket']; ?></td>    
            <td style="padding-left: 50px;"><?php echo $fila['status']; ?></td>  
            <td style="padding-left: 50px;"><?php echo $fila['log']; ?></td> 
            <td style="padding-left: 50px;"><button class="btn-primary" onclick="reenviar(<?php echo $fila['idlog']; ?>)">Reenviar</button></td> 

        </tr>
        <?php
    }
    ?>
</tbody>