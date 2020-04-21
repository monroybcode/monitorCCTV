<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include '../resources/connection/conexion.php';
include "../utils/constantes.php";

$mysqli->query("SET NAMES 'utf8'");

$sql = "select * from tickets where id_ticket=" . $_GET['id'];
$resultado = $mysqli->query($sql);
$ticket = $resultado->fetch_assoc();

$sqlpadre = "select nombre from categoria where id=" . $ticket['categoria_1'];
$resultado = $mysqli->query($sqlpadre);
$padre = $resultado->fetch_assoc();

$sqlprincipal = "select nombre from categoria where id=" . $ticket['categoria_2'];
$resultado = $mysqli->query($sqlprincipal);
$principal = $resultado->fetch_assoc();

$sqlsub = "select nombre from categoria where id=" . $ticket['categoria_3'];
$resultado = $mysqli->query($sqlsub);
$subcategoria = $resultado->fetch_assoc();

$sqlusuario = "select * from usuarios where id_usuario=" . $ticket['usuario_registra'];
$resultado = $mysqli->query($sqlusuario);
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Star Médica | Detalle Tickets Mobile</title>
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <meta name="HandheldFriendly" content="true" />

        <?php include '../resources/components/includes-mobile.php'; ?>

        <script>
            $('#rechazar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('whatever');
                var modal = $(this);
                modal.find('.modal-title').text('New message to ' + recipient);
                modal.find('.modal-body input').val(recipient);
            });

            function autoriza() {
                swal({
                    title: "Estas seguro?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                }).then(function () {
                    var id = $("#id").val();
                    $.ajax({
                        type: "POST",
                        url: "resources/controller/autoriza.php",
                        data: {id: id},

                        success: function (data) {
                            correcto2();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Error!", "Intentelo mas Tarde", "error");
                        }
                    });
                });
            }

            function correcto2() {
                swal({
                    title: "Correcto",
                    text: "Ticket Autorizado Correctamente",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: false,
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function () {

                    document.location.href = 'Mobile-tickets.php';
                });
            }
        </script>
    </head>
    <body id="mobile2">
        <?php
        include './resources/modal/modal-rechazo.php';
        ?>

        <div class="profile-head ">

            <img class="ima-star" src="../resources/images/starmedica.png"/>
            <div class="top-message">
                <?php echo $_SESSION['nombre']; ?>
            </div>
            <div class="enmedio-mensaje">
                # <?php echo $_GET['id']; ?>
                <input name="id" id="id" value="<?php echo $_GET['id']; ?>" hidden="true">
            </div>
        </div>

        <div class="4-col-grids mobile-body" style="padding-bottom:65px;">
            <br><br>

            <div class="container">
                <div class="row">
                    <div class="content-data-side col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <div id="titulo_datos">
                            <label  id="titulo" class="rounded">Datos generales &nbsp;</label>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <span class="fa fa-tags"><?php echo " " . $padre['nombre'] . " | ";
                echo $principal['nombre'] . " | ";
                echo $subcategoria['nombre']; ?></span></br>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="content-data-side col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="titulo_datos">
                            <label id="titulo" >Datos del solicitante &nbsp;</label> 
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <span class="fa fa-user" ><?php echo " " . $usuario['nombre'] . " | "; ?></span> <span class="glyphicon glyphicon-calendar"><?php $fecha = new DateTime($ticket['fecha_registro']);
                echo $fecha->format('d/m/Y-H:i'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="content-data-side col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="titulo_datos">
                            <label id="titulo">Comentarios &nbsp;</label>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <span class="fa fa-commenting"><?php echo " " . $ticket['comentarios']; ?></span></br>
                        </div>
                    </div>
                </div>

            </div>


            <div class="container2">



                <div class="row" >
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                        <button type="button" class="btn btn-success btn-lg btn-block" onclick="autoriza()" >Autorizar</button>
                    </div>
                </div>
                <br>
                <div class="row" >
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-secondary btn-lg btn-block" style="background-color: #FA5858; color: white;" data-toggle="modal" data-target="#rechazar">Rechazar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="footer">

            <table class="full-table">
                <tr>
                    <td>
                        <a href="Mobile-tickets.php">  
                            <div class="back-mobile">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> REGRESAR
                            </div>
                        </a>
                    </td>
                    <td>
                        <a href="resources/controller/controller-mobile-logout.php">
                            <div class="link-mobile">
                                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> CERRAR SESIÓN
                            </div>
                        </a>
                    </td>
                </tr>
            </table>

            <div id="adn"></div>
            <div class="grey-space">
                <img src="../resources/images/espacio-humano.png" class="slogan-left" alt=""/>
            </div>
        </div>


    </body>
</html>
