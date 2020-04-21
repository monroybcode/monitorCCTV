<?php

session_start();
include '../../../resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
$email = "";
$jason_data['msj'] = '';
            

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $splitmail = explode("@", $email);


    if ($splitmail[1] == "starmedica.com" || $splitmail[1] == "gmail.com") {
        $sql = "SELECT usuarios.id_usuario, usuarios.email,usuarios.nombre, usuarios.rol FROM usuarios WHERE  usuarios.email='$email'";

        $resulSet = $mysqli->query($sql);
        $fila = $resulSet->fetch_assoc();
        $filas = $resulSet->num_rows;

        if ($filas > 0) {
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['email'] = $fila['email'];
            $_SESSION['nombre'] = $fila['nombre'];
            //$_SESSION['nombre_col'] = $fila['id_col'];
           // $_SESSION['nombre_area'] = $fila['nombre_area'];
            $_SESSION['rol'] = $fila['rol'];
            $_SESSION['usr_email'] = $fila['email'];

            header("location: ../../Mobile-tickets.php");
        } else {
            //usuario no valido
            header("location: index.php?email=Usuario no valido por favor vuelve a intentarlo");
            
        }
    } else {
        //correo invalido
        echo "Correo invalido";
       header("location: index.php?email=Correo invalido por favor vuelve a intentarlo");
        
    }
    $jason_data['msj'] = $msj;
    echo json_encode($jason_data);
}



