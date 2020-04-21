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

            $(function () {
                $('#repite').keyup(function () {
                    var _this = $('#repite');
                    var _user = $('#password').val();
                    if (_this.val() != _user) {
                        _this.attr('style', 'background:#FF4A4A');
                    } else {
                        _this.attr('style', 'background:white');
                    }

                });
            });

            $(function () {
                $("#formcambiapass").submit(function (e) {
                    show_loader();
                    e.preventDefault();
                    $.ajax({
                        url: "resources/controller/cambiapass.php",
                        type: 'post',
                        data: $("#formcambiapass").serialize(),
                        success: function (data) {
                            if (data.trim() === '1') {
                                show_alert("El password ha sido cambiado", "alert-info");

                                $("#formcambiapass")[0].reset();
                                $("#numempleado").focus();
                            } else {
                                show_alert("Ocurrio un error, verifique que los datos son correctos", "alert-danger");

                                $("#formcambiapass")[0].reset();
                                $("#numempleado").focus();

                            }
                            hide_loader();
                        }
                    });

                });

            });
        </script>  
    </head>

    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>


        <?php
        $sql = "select u.nombre, u.login, u.puesto, u.email, cv.descripcion as rol from usuarios u inner join catalogo_valor cv on u.rol=cv.id and cv.catalogo=1 where u.id_usuario='" . $_SESSION['id_usuario'] . "'";
        $resultado = $mysqli->query($sql);
        $fila = $resultado->fetch_assoc();
        ?>

        <div class="content-wrapper">
            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 panel-my-user-control">
                <div class="">
                    <div class="box-header  box-head-data">
                        <label class="box-title title-box-ticket">Datos de la cuenta &nbsp;</label>
                    </div>

                    <div class="box-body ">
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><span class="fa fa-user-circle-o" style="font-size: 3em; color: #337ab7;"></span></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Usuario:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <span><?php echo $fila['login']; ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Nombre:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <span><?php echo $fila['nombre']; ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Puesto:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <span><?php echo $fila['puesto']; ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Correo:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <span><?php echo $fila['email']; ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Rol:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <span><?php echo $fila['rol']; ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 no-padding">
                                    <label>Grupos:</label>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-7 no-padding">
                                    <?php
                                    $sqlGrupos = "select g.nombre from usuario_grupos ug inner join grupos g on g.id_grupo=ug.id_grupo where ug.id_usuario='" . $_SESSION['id_usuario'] . "';";
                                    $resultado_grupos = $mysqli->query($sqlGrupos);

                                    while ($filaGrupo = $resultado_grupos->fetch_assoc()) {
                                        echo '<span>' . $filaGrupo['nombre'] . '</span>';
                                        echo '<br>';
                                    }
                                    ?>                               
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <form class="form-horizontal"  method="post" id="formcambiapass" name="formcambiapass">
                                <div class="form-group">
                                    <label class="control-label col-sm-6" for="passwordactual">Contraseña actual:</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="passwordactual" required="true" name="passwordactual" placeholder="Contraseña actual" >    
                                    </div>
                                </div>

                                <br>
                                <div class="form-group">
                                    <label class="control-label col-sm-6" for="password">Nueva contraseña:</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="password" required="true" name="password" placeholder="Nueva contraseña" >    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-6" for="repite">Confirma contraseña:</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control"  id="repite" required="true" name="repite" placeholder="Repite nueva contraseña" >      
                                    </div>
                                </div>

                                <div class="form-group" style="margin: 0px !important;">
                                    <div class="col-sm-offset-4 col-sm-8 no-padding">
                                        <button type="submit" class="btn btn-primary pull-right">Cambiar contraseña</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="">
                    <div class="box-header  box-head-data">
                        <label class="box-title title-box-ticket">Categorías &nbsp;</label>
                    </div>

                    <div class="box-body ">
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><span class="fa fa-tags" style="font-size: 3em; color: #337ab7;"></span></div>

                        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 no-padding">
                            <?php
                            $sqlCategorias = "select c.nombre 
                                from usuario_categorias uc 
                                inner join categoria c on c.id=uc.id_categoria 
                                where uc.id_usuario='" . $_SESSION['id_usuario'] . "'
                                order by c.nombre";

                            $resultado_categorias = $mysqli->query($sqlCategorias);

                            while ($fila_categoria = $resultado_categorias->fetch_assoc()) {
                                echo '<div class="col-lg-4">';
                                echo '<span class="glyphicon glyphicon-chevron-right"></span><span>' . $fila_categoria['nombre'] . '</span>';
                                echo '</div>';
                            }
                            ?>                            
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>