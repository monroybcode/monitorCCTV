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
        <script>
            $(document).ready(function () {
                $.ajax({
                    url: "resources/tablas/tbl-tickets-usuarios.php",
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#contenedor_tu").html(data);
                        $(function () {
                            $("#accordion").accordion({
                                collapsible: true
                            });
                        });
                    }
                });
            });

        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">

        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" id="contenedor_tu">

            </div>
        </div>

        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>