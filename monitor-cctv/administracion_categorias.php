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
              
                
                remove_class_navbar();
                $(".link-administracion").addClass("active");
                carga_tabla_categorias();
                $('input[type=radio][name=ind_tipo]').change(function () {
                    if (this.value == '1') {
                        $("#DetallePrincipal").hide();
                        $("#categoria_padre").prop('selectedIndex', 0);
                        $(".subcategorias_dinamicas").html('</br>');
                        $('#categoria_padre').parent().removeClass("has-error");
                        $("#catpaddiv").html("");
                    } else if (this.value == '2') {
                        $("#DetallePrincipal").show();
                    }
                });
                $("#agregar_subcategoria").click(function () {
                    $(".subcategorias_dinamicas").append('<div><input type="text" name="subcategorias[]"/><a href="#" class="remove_field">&nbsp;<i class="fa fa-times" style="color:red; font-size:20px;" aria-hidden="true"></i></a></div>')
                });
                $(document).on('click', '.remove_field', function (e) {
                    e.preventDefault();
                    $(this).parent('div').remove();
                });
            });

            function carga_tabla_categorias() {
                $("#table_categorias").load("resources/tablas/tbl-categorias.php");
            }

            function nueva_categoria() {
                $("#DetallePrincipal").show();
                limpiar();
                actualizarselectpadre();
                $("#id_categoria").val('');
               // $("#ind_tipo_principal").removeAttr('checked');
                $("#ind_tipo_padre").removeAttr('disabled');
                $("#ind_activo").attr('disabled', 'disabled');
                $("#ind_activo").attr('checked', 'checked');
                $("#ind_tipo_principal").attr('checked', 'checked');
                $("#modal-agrega-categoria").find('form')[0].reset();
                $(".subcategorias_dinamicas").html('</br>');
               // $("#DetallePrincipal").hide();
                $("#modal-agrega-categoria").modal("show");
            }

            function editar_categoria(id) {
                var id_grupo = 0; 
                var ind_activo = false;
                limpiar();
                //actualizarselectpadre();
                $("#modal-agrega-categoria").find('form')[0].reset();
                $(".subcategorias_dinamicas").html('</br>');

                $("#id_categoria").val(id);

                $.ajax({
                    type: "POST",
                    url: "utils/funciones.php",
                    data: "id_categoria=" + id + "&opcion=traer_detalle_categoria",
                    async: false,
                    success: function (data) {
                        console.log(data);
                        var arrData = JSON.parse(data);
                        $("#nombre_categoria").val(arrData.nombre);
                        $("#url_formatos").val(arrData.url_formatos);
                        $("#desc_ayuda").val(arrData.desc_ayuda);
                        $("#categoria_padre").val(arrData.categoria_padre);
                        id_grupo = arrData.id_grupo;
                        ind_activo = arrData.esta_activo;
                    }
                });


                $("#ind_activo").removeAttr('disabled');
                $("#ind_tipo_padre").removeAttr('checked');
                $("#ind_tipo_padre").attr('disabled', 'disabled');
                $("#ind_tipo_principal").attr('checked', 'checked');

                if (ind_activo) {
                    $("#ind_activo").attr('checked', 'checked');
                } else {
                    $("#ind_activo").removeAttr('checked');
                }

                if (id_grupo !== 0) {
                    $("#grupo").val(id_grupo);
                }

                $.ajax({
                    type: "POST",
                    url: "utils/funciones.php",
                    data: "id=" + id + "&opcion=buscar_subcategorias",
                    success: function (data) {

                        $(".subcategorias_dinamicas").html("</br>" + data);
                    }
                });
                $("#DetallePrincipal").show();
                $("#modal-agrega-categoria").modal("show");


            }
            
            function guardar_categoria() {
                var errores = 0;
                if ($("#nombre_categoria").val().length == 0) {
                    errores = 1;
                    $('input[name=nombre_categoria]').parent().addClass("has-error");
                    $("#nombrediv").html("Debe introducir el nombre de categoria");
                }
                if ($('input[name=ind_tipo]:checked').val() == 2) {
                    
                }

                if (errores == 0) {
                    var formData = $("#frm_categoria").serialize() + "&opcion=alta_categoria";
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones.php",
                        data: formData,
                        success: function (data) {
                            if (data.trim() === "1") {
                                hide_loader();
                                show_alert("Los datos se guardaron exitosamente", "alert-info");

                                $("#modal-agrega-categoria").modal('hide');
                                $("#modal-agrega-categoria").find('form')[0].reset();
                                carga_tabla_categorias();
                            } else {
                                hide_loader();
                                show_alert("Ocurrio un error al guardar" + data, "alert-danger");
                            }
                        },
                        error: function () {
                            show_alert("Ocurrio un error al guardar", "alert-danger");
                            hide_loader();
                        }
                    });
                }
            }
            
            function limpiar() {
                $('input[name=nombre_categoria]').parent().removeClass("has-error");
                $("#nombrediv").html("");
                $('#categoria_padre').parent().removeClass("has-error");
                $("#catpaddiv").html("");
            }
            
            function actualizarselectpadre() {
                $.ajax({
                    type: "POST",
                    url: "utils/funciones.php",
                    data: "opcion=actualizarselectpadre",
                    success: function (data) {
                        $("#divselectpadre").html('');
                        $("#divselectpadre").html(data);

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

        <div class="content-wrapper" style="padding-bottom:20px;">

            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                <div class="contenido_vista_roles">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 20px 15px; padding-top: 0;">
                        <h3 class="no-margin">Categorias</h3>

                        <button type="button"  class="btn btn-primary pull-right" onclick="javascript:nueva_categoria()">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar Categoria
                        </button>
                    </div>

                    <div class="col-lg-12" style="padding-bottom:60px;">
                        <table class="table-s table table-hover table-responsive tbl-det-tickets"  id="table_categorias" name="table_categorias">
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <?php include './resources/modals/modal-agrega-categoria.php'; ?>
        <?php //include 'resources/modals/modal-funciones-rol.php';  ?>
        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>