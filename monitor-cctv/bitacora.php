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
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>

        <div class="content-wrapper">

            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="margin-bottom: 70px;">

                <h3 class="no-margin">Bitácora</h3>

                <table class="table-s table table-responsive table-hover text-muted">
                    <thead class="box box-primary">
                        <tr>
                            <th>Reporte</th>
                            <th>Fecha registro</th>
                            <th>Cod. origen</th>
                            <th>Id origen</th>
                            <th>Descripción</th>
                            <th>Campos</th>
                            <th>Cambios</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $sql = "select b.ticket, b.fecha_registro, b.cod_origen, b.id_origen, b.descripcion, b.campos, b.cambios, u.nombre "
                                . " from bitacora b inner join usuarios u on b.usuario_evt=u.id_usuario order by b.id desc;";
                        $resultado = $mysqli->query($sql);

                        while ($fila = $resultado->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $fila['ticket']; ?></td>
                                <td><?php echo $fila['fecha_registro']; ?></td>
                                <td><?php echo $fila['cod_origen']; ?></td>
                                <td><?php echo $fila['id_origen']; ?></td>
                                <td><?php echo $fila['descripcion']; ?></td>
                                <td><?php echo $fila['campos']; ?></td>
                                <td><?php echo $fila['cambios']; ?></td>
                                <td><?php echo $fila['nombre']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>


                    </tbody>
                </table>
            </div>




        </div>



        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>