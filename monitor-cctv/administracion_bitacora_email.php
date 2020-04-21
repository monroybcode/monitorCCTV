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
        <script type="text/javascript">
            $(document).ready(function () {

                $("#filtrar_p").css("display", "block");
                $('#filtrar_p').keyup(function () {

                    var rex = new RegExp($(this).val(), 'i', );

                    $('.buscar2 tr').hide();
                    $('.buscar2 tr').filter(function () {

                        return rex.test($(this).text());
                    }).show();


                });


            });

            function carga_tabla_categorias() {
                $("#table_bitacora").load("resources/tablas/tabla-bitacora.php");
            }


        </script>

    </head>

    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'utils/funciones.php'; ?>
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>


        <div class="content-wrapper" style="padding-bottom:20px;">

            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                <div class="contenido_vista_roles">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px 15px; padding-top: 0;">
                        <h3 class="no-margin">Bitacora Email</h3>
                    </div>
                    <div class="col-md-6" style="padding-bottom: 10px;">
                        <input id="filtrar_p" type="text" class="form-control" placeholder="Busqueda" style="display: none;">
                    </div>
                    <div class="col-lg-12" style="padding-bottom:60px;">
                        <table class="table-s table table-hover table-responsive tbl-det-tickets"  id="table_bitacora" name="table_categorias">
                            <?php include './resources/tablas/tabla_bitacora_email.php'; ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>


        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>