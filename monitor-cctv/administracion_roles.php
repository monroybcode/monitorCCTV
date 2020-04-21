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
            var totalItemFunciones = 1;

            $(document).ready(function () {
                remove_class_navbar();
                $(".link-administracion").addClass("active");

                carga_tabla_roles();
                //cargar_funciones_bd();

                $("#agrega_funcion_rol").click(function (e) {
                    e.preventDefault();

                    $("#tbl_lista_funciones").append("<tr><td style='width=80%;'>"
                            + $("#funcion option:selected").text()
                            + "</td><td style='text-align: center; width=20%;'><a href='#' onclick='deleteRow(this,0); return false;'>"
                            + "<img src='resources/images/eliminar.png' height='20px'></a></td></tr>");

                    $("#tbl_funciones").append(crearEventoRolFuncion($("#funcion").val(), "ADD"));
//                    $("#funcion").val('');
//                    $("#funcion").focus();
                    totalItemFunciones++;
                    $("#total_funciones").val(totalItemFunciones);
                });
                
                $('#modal-rol-funciones').on('shown.bs.modal', function (e) {
                    $(".toggleRolFn").bootstrapToggle();
                })

            });


            function nuevo_rol() {
                limpiar();
                $("#modal-agrega-rol").find('form')[0].reset();
                $("#id_rol").val("");
                $("#modal-agrega-rol").modal('show');
            }

            function edita_rol(id_rol, nombre) {
                limpiar();
                $("#id_rol").val(id_rol);
                $("#nombre_rol").val(nombre);

                $("#modal-agrega-rol").modal('show');

                return false;
            }

            function guardar_rol() {
                if ($("#nombre_rol").val().length == 0) {
                    errores = true;
                    $('input[name=nombre_rol]').parent().addClass("has-error");
                    $("#nombrediv").html("Debe introducir el nombre de rol");
                } else {
                    var formData = $("#frm_rol").serialize() + "&opcion=alta_rol";
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones.php",
                        data: formData,
                        success: function (data) {
                            if (data.trim() === "1") {
                                hide_loader();
                                show_alert("Los datos se guardaron exitosamente", "alert-info");

                                $("#modal-agrega-rol").modal('hide');
                                $("#modal-agrega-rol").find('form')[0].reset();
                                carga_tabla_roles();
                            } else {
                                hide_loader();
                                show_alert("Ocurrio un error al guardar", "alert-danger");
                            }
                        },
                        error: function () {
                            show_alert("Ocurrio un error al guardar", "alert-danger");
                            hide_loader();
                        }
                    });
                }
            }

            function carga_tabla_roles() {
                $("#table_roles").load("resources/tablas/tbl-roles.php");
            }

            function limpiar() {
                $('input[name=nombre_rol]').parent().removeClass("has-error");
                $("#nombrediv").html("");
            }

            function funciones_rol(id_rol, nombre) {
                $("#titulo").html("Funciones de rol '" + nombre + "'");
                $("#id_rol_tratado").val(id_rol);
                $("#tbl_lista_funciones").load('resources/tablas/tbl-funciones-rol.php', {id_rol: id_rol});
                $("#modal-rol-funciones").modal('show');
                
            }

            function guardar_funciones_rol() {
                var formData = $("#frm_funciones_de_rol").serialize() + "&opcion=guarda_funciones_rol";

                $.ajax({
                    type: "POST",
                    url: "utils/funciones.php",
                    data: formData,
                    success: function (data) {
                        show_alert("Los datos se guardaron exitosamente", "alert-info");
                        $("#modal-rol-funciones").modal('hide');
                    },
                    error: function () {
                        show_alert("Ocurrio un error al guardar", "alert-danger");
                    }
                });
            }

            function cargar_funciones_bd() {
                $.ajax({
                    url: "utils/funciones.php",
                    data: "opcion=consulta_funciones_bd",
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#funcion").html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("error" + textStatus);
                    }
                });
            }

        </script>

    </head>

    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">

        <?php include 'utils/funciones.php'; ?>
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>

        <div class="content-wrapper">

            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                <div class="contenido_vista_roles">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px 15px; padding-top: 0;">
                        <h4 class="no-margin">Roles</h4>

                        <button type="button"  class="btn btn-primary pull-right" onclick="javascript:nuevo_rol()">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar Rol
                        </button>
                    </div>

                    <div class="col-lg-12">
                        <table class="table-s table table-hover table-responsive tbl-det-tickets" id="table_roles" name="table_roles">
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <?php include './resources/modals/modal-agrega-rol.php'; ?>
        <?php include 'resources/modals/modal-funciones-rol.php'; ?>
        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>