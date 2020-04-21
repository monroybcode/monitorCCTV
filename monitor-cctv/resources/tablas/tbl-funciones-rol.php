<?php

require('../connection/conexion.php');
$mysqli->query("SET NAMES 'utf8'");

$id_rol = $_POST["id_rol"];

//$funciones = "SELECT rf.id_funcion, rf.id_rol, f.nombre from rol_funciones rf inner join funciones f on rf.id_funcion=f.id_funcion where rf.id_rol='$id_rol';";
$funciones = "select f.id_funcion, ifnull(rf.id_funcion,0) as id_fn_asign, f.nombre 
            from funciones f
            left join rol_funciones rf on f.id_funcion = rf.id_funcion and rf.id_rol = '$id_rol'
            where f.ind_activo = true and f.tp_funcion = 1 
            order by f.nombre;";

$resultado = $mysqli->query($funciones);
$maxId = 0;


while ($fila = $resultado->fetch_assoc()) {
    if($fila['id_funcion'] > $maxId){
        $maxId = $fila['id_funcion'];
    }
    echo '<div class="row no-padding">
            <div class="col-md-10">
                <label>
                    '.$fila['nombre'].'
                </label>
            </div>
            <div class="col-md-2">
                <input id="fn-'.$fila['id_funcion'].'" name="fn-'.$fila['id_funcion'].'" class="toggleRolFn" type="checkbox" data-toggle="toggle" '.($fila['id_fn_asign'] > 0 ? 'checked':'').' data-size="mini" data-on="Si" data-off="No">
            </div>
        </div><hr style="margin: 5px 0px;"/>';
}

echo '<input id="total_funciones" name="total_funciones" type="hidden" value="'.$maxId.'">';

?>