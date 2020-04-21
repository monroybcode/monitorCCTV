<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>
        
<?php require 'resources/components/includes.php'; ?>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">

        <?php include 'resources/components/header-2.php'; ?>
<?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>

        <div class="content-wrapper">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 container-detalles-ticket">

                <br/>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-danger title-table-list">No asignados</ul>

                            <a href="ticket.php" class="list-group-item">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <span style="font-weight: bold; font-size: 0.8em;">1000001</span>
                                        <br>
                                        
                                    </div>
                                    <div class="col-lg-10">
                                        <span style="font-size: 1.2em; font-weight: bold;">
                                            Anulación de facturas
                                        </span>
                                        <br>
                                        <span style="font-size: 0.8em;">
                                            1113 - QRO &nbsp;&nbsp;&nbsp; Ultima act: <strong>Hace 20 min.</strong>
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <a href="ticket.php" class="list-group-item">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <span style="font-weight: bold; font-size: 0.8em;">1000002</span>
                                    </div>
                                    <div class="col-lg-10">
                                        <span style="font-size: 1.2em; font-weight: bold;">
                                            Paquetes - Alta, baja y modificación
                                        </span>
                                        <br>
                                        <span style="font-size: 0.8em;">
                                            1101 - MLM &nbsp;&nbsp;&nbsp; Ultima act: <strong>Hace 30 min.</strong>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-warning title-table-list">Pendientes de mi acción</ul>

                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-success title-table-list">Reportados por mi</ul>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-warning title-table-list">Asignados a mi</ul>

                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-info title-table-list">Generados en mi área</ul>

                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="list-group">
                            <ul class="list-group-item list-group-item-warning title-table-list">Resueltos por mi</ul>

                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                            <a href="" class="list-group-item">Titulo del Reporte</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>