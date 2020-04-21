<?php
$mysqli = new mysqli("localhost", "gsm_user", "Desarroll0.18", "gsm_service_cctv_test");
//$mysqli = new mysqli("localhost", "gsm_user", "Desarroll0.18", "gsm_service_ish_app");
//$mysqli = new mysqli("localhost","pruebaz","12345", "cctv2");
//$mysqli = new mysqli("localhost", "root", "", "gsmcctv");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>