<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");

$id = "";
$operacion = "";
$tipo = "";


if (isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];
}

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}

switch ($operacion) {

    case 'eliminar':


        $id = $_POST['id'];
        eliminar($id, $tipo);
        break;

    case 'agregar':

        if (isset($_POST['tipo'])) {
            $tipo = $_POST['tipo'];
        }

        if ($tipo === 'email') {

            if (isset($_POST['nombre_email'])) {
                $id = $_POST['nombre_email'];
            }
            
            if (isset($_POST['nombre_hospital'])) {
                $hospital = $_POST['nombre_hospital'];
            }

            agregar($id, $hospital);
        }

        /* else
          if ($tipo === 'or') {
          if (isset($_POST['nombre'])) {
          $nombre = $_POST['nombre'];
          }
          if (isset($_POST['estatus'])) {
          $estatus = $_POST['estatus'];
          }
          agregar($nombre, $estatus, $tipo);
          } */

        break;

    case 'consultar':


        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        consultar($id, $tipo);
        break;

    
      case 'editar':
     

      if (isset($_POST['id_oculto'])) {
      $id = $_POST['id_oculto'];
      }
      
      if (isset($_POST['horas'])) {
      $horas = $_POST['horas'];
      }
      editar($id, $horas);
      break; 
}

function eliminar($id, $tipo) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'utf8'");

  
    if ($tipo === 'email') {
        $sql_eliminar = "delete from  matriz_distribucion where idmatriz_distribucion='$id'";
    }

    $mysqli->query($sql_eliminar);
}

function agregar($id, $hospital) {


    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'utf8'");
    $fecha_actual = strftime("%Y-%m-%d-%H-%M-%S", time());

    $jason_data['correcto'] = "";
    $jason_data['id_usr'] = "";


        $consulta = "SELECT * FROM usuarios where id_usuario='$id'";
        //echo $consulta;
        $resultado = $mysqli->query($consulta);
        $fila_con = $resultado->fetch_assoc();

        $nombre = $fila_con['nombre'];
        $email = $fila_con['email'];

        $sql_insert_usuarios = "INSERT INTO matriz_distribucion (idmatriz_distribucion, nombre, email, hospital)
                                VALUES ('', '$nombre', '$email','$hospital')";
    

//  echo $sql_insert_usuarios;
    $mysqli->query($sql_insert_usuarios);
    $idUsr = $mysqli->insert_id;



    if ($idUsr > 0) {
        $jason_data['id_usr'] = $idUsr;
        $jason_data['correcto'] = "<strong style='text-align:center;'>Se guardo con exito<strong>";
    } else {
        $jason_data['correcto'] = "Hubo un error intentalo nuevamente";
    }

    echo json_encode($jason_data);
}

function consultar($id, $actualizar_tipo) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'utf8'");

    $jason_data['hospital'] = "";
    $jason_data['categoria'] = "";
    $jason_data['horas'] = "";
    $jason_data['id'] = "";

    if ($actualizar_tipo === 'horas') {
        $query = "SELECT 
                       id_tiempo, tiempos_resolucion.tiempo, hospital.nombre as hospital, categoria.nombre
                     FROM
                         tiempos_resolucion
                             LEFT JOIN
                         hospital ON tiempos_resolucion.id_hospital = hospital.id
                             LEFT JOIN
                         categoria ON tiempos_resolucion.categoria = categoria.id where id_tiempo='$id'";
        $resultado = $mysqli->query($query);
        $res = $resultado->fetch_assoc();

        $jason_data['hospital'] = $res['hospital'];
        $jason_data['categoria'] = $res['nombre'];
        $jason_data['horas'] = $res['tiempo'];
        $jason_data['id'] = $id;
    }



    echo json_encode($jason_data);
}

function editar($id, $horas) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'utf8'");

   
        $query = "update tiempos_resolucion set tiempo='$horas' where id_tiempo='$id'";
   
    $mysqli->query($query);
    echo true;
}

