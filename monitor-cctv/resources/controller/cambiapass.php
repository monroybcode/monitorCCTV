<?php

session_start();
require('../connection/conexion.php');

if (isset($_POST["password"])) {
    $pass = $_POST["password"];
}

if (isset($_POST["passwordactual"])) {
    $passactual = $_POST["passwordactual"];
}


$qry = "select *
        from usuarios 
        where id_usuario='" . $_SESSION['id_usuario'] . "' and password = '" . sha1($passactual) . "'";


$res = mysqli_query($mysqli, $qry);
$num_row = mysqli_num_rows($res);

if ($num_row == 1) {
    $row = mysqli_fetch_assoc($res);

    $sqlquery = "UPDATE usuarios set password = '" . sha1($pass) . "' WHERE id_usuario='" . $_SESSION['id_usuario'] . "'";

    echo $mysqli->query($sqlquery);
} else {
    echo "error";
}
?>
