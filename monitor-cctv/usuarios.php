<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
include 'utils/funciones.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>
        <?php require 'resources/components/includes.php'; ?>
        <script type="text/javascript">
            $(document).ready(function (e) {
                remove_class_navbar();
                $(".link-administracion").addClass("active");

                $.ajax({
                    url: "resources/tablas/tbl-usuarios.php",
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $(".table_users").html(data);
                    }
                });

                $("#btn-reset-pass").click(function (e) {
                    resetpass();
                });
                $("#consultarUsr").click(function (e) {

                    $.ajax({
                        url: "resources/tablas/tbl-usuarios.php",
                        data: $("#filtros").serialize(),
                        type: 'POST',
                        success: function (data, textStatus, jqXHR) {
                            $(".table_users").html(data);
                        }
                    });
                });
                $(document).on('click', '#consultarUsrd', function () {
                    $.ajax({
                        url: "resources/tablas/tbl-usuarios-duplicar.php",
                        data: $("#filtrosb2").serialize(),
                        type: 'POST',
                        success: function (data, textStatus, jqXHR) {
                            $(".tabla-usuarios-busq").html(data);
                            $(".tabla-usuarios-busq").show();
                        }
                    });
                });
                $(document).on('click', '#notificaciones', function (e) {
                    if ($(this).is(':checked')) {
                        $("#email").removeAttr('disabled');
                        $("#ntf_tipos").removeAttr('disabled');
                        $("#ntf_tipos_usuario").removeAttr('disabled');

                        $("#boton_antf").removeAttr('disabled');
                        $("#boton_qntf").removeAttr('disabled');
                    } else {
                        $("#email").attr('disabled', 'disabled');
                        $("#email").val('');
                        $("#ntf_tipos").attr('disabled', 'disabled');
                        $("#ntf_tipos_usuario").attr('disabled', 'disabled');
                        $("#boton_antf").attr('disabled', 'disabled');
                        $("#boton_qntf").attr('disabled', 'disabled');
                        $("#ntf_tipos_usuario").val('');
                    }




                });
                $(document).on('click', '#notificaciones2', function (e) {
                    if ($(this).is(':checked')) {
                        $("#email2").removeAttr('disabled');
                    } else {
                        $("#email2").attr('disabled', 'disabled');
                        $("#email2").val('');
                    }
                });
            });

            function nuevousuario() {
                $("#btn-reset-pass").hide();
                limpiar();
                $("#hospitales_usuario").html('');
                $("#hospitales").html('');
                $("#grupos_usuario").html('');
                $("#grupos").html('');
                $("#categorias_usuario").html('');
                $("#categorias").html('');
                $("#email").attr('disabled', 'disabled');

                $("#ntf_tipos").attr('disabled', 'disabled');
                $("#ntf_tipos_usuario").attr('disabled', 'disabled');
                $("#boton_antf").attr('disabled', 'disabled');
                $("#boton_qntf").attr('disabled', 'disabled');
                $("#ntf_tipos_usuario").html('');

                $.ajax({
                    url: "utils/funciones.php",
                    type: 'POST',
                    data: "opcion=hosp",
                    success: function (data, textStatus, jqXHR) {
                        $("#hospitales").html(data);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    type: 'POST',
                    data: "opcion=sql_grupos",
                    success: function (data, textStatus, jqXHR) {
                        $("#grupos").html(data);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    type: 'POST',
                    data: "opcion=sql_categorias",
                    success: function (data, textStatus, jqXHR) {
                        $("#categorias").html(data);
                    }
                });


                $.ajax({
                    url: "utils/funciones.php",
                    type: 'POST',
                    data: "opcion=ntf_tipos",
                    success: function (data, textStatus, jqXHR) {
                        console.log(data);
                        $("#ntf_tipos").html(data);
                    }
                });



                $("#modal-datos-usuario").find('form')[0].reset();
                $("#idusuario").val("");
                $("#modal-datos-usuario").modal('show');
            }

            function duplicarUsuario() {
                $(".busqueda-usuario").show();
                $(".tabla-usuarios-busq").hide();
                $(".tabla-usuarios-busq").html("");
                $("#titulo-modal-duplicar").html("Duplicar Usuario")
                $(".form-duplicar").hide();
                $("#modal-duplicar-usuario").modal('show');
            }

            function duplicarUsuarioP2(id_usuario, nombre, login, email, rol, activo, puesto, notificaciones) {
                $("#btn-reset-pass2").show();
                limpiar2();
                $("#hospitales_usuario2").html('');
                $("#hospitales2").html('');

                $("#titulo-modal-duplicar").html('Duplicar Usuario  de ' + nombre)
                $("#id_usuario2").val('');
                $("#login2").val('');
                $("#nombre2").val('');
                /*if(email != ""){
                 $('#email2').removeAttr('disabled');
                 }*/
                $("#email2").val('');
                $("#puesto2").val('');
                $("#rol2").val(rol);
                $("#activo2").prop("checked", activo);
                $("#email2").attr("disabled", "disabled");
                if ($("#notificaciones2").is(':checked')) {
                    $("#notificaciones2").prop("checked", false);
                }

                $(".busqueda-usuario").hide();
                $(".form-duplicar").show();

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=hospuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#hospitales_usuario2").html(data['asignados']);
                        $("#hospitales2").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=gruposuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#grupos_usuario2").html(data['asignados']);
                        $("#grupos2").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=categoriasuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#categorias_usuario2").html(data['asignados']);
                        $("#categorias2").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=ntf",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#ntf_tipos_usuario2").html(data['asignados']);
                        $("#ntf_tipos2").html(data['noasignados']);
                    }
                });
            }

            function eliminarUsuario(id_usuario) {
               

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=eliminar",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                            swal({
                            title: '',
                            confirmButtonText: 'ok',
                            type: 'success',
                            text: 'Se elimino la cuenta satisfactoriamente',
                            showLoaderOnConfirm: true,
                            allowOutsideClick: false
                        });
                       location.reload(); 
                    }
                });

         
            }


               function editaUsuario(id_usuario, nombre, login, email, rol, activo, puesto, notificaciones) {
                $("#btn-reset-pass").show();
                limpiar();
                $("#hospitales_usuario").html('');
                $("#hospitales").html('');

                $("#id_usuario").val(id_usuario);
                $("#login").val(login);
                $("#nombre").val(nombre);
                if (email != "") {
                    $('#email').removeAttr('disabled');
                    $("#ntf_tipos").removeAttr('disabled');
                    $("#ntf_tipos_usuario").removeAttr('disabled');

                    $("#boton_antf").removeAttr('disabled');
                    $("#boton_qntf").removeAttr('disabled');
                }
                $("#email").val(email);
                $("#puesto").val(puesto);
                $("#rol").val(rol);
                $("#activo").prop("checked", activo);
                $("#notificaciones").prop("checked", notificaciones);

                $("#modal-datos-usuario").modal('show');

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=hospuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#hospitales_usuario").html(data['asignados']);
                        $("#hospitales").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=gruposuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#grupos_usuario").html(data['asignados']);
                        $("#grupos").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=categoriasuser",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#categorias_usuario").html(data['asignados']);
                        $("#categorias").html(data['noasignados']);
                    }
                });

                $.ajax({
                    url: "utils/funciones.php",
                    data: "id_usuario=" + id_usuario + "&opcion=ntf",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        $("#ntf_tipos_usuario").html(data['asignados']);
                        $("#ntf_tipos").html(data['noasignados']);
                    }
                });
            }
            
            function guardarDatosDuplicadog() {
                show_loader();
                var bandera = false;
                bandera = validadup();

                if (bandera == false) {
                    $("#add_erradduser").html("");
                    var f = $("#frmUsuario2");
                    var formData = f.serialize();

                    var hospitales = "";
                    var grupos = "";
                    var categorias = "";
                    var ntf="";

                    for (i = 0; i < document.getElementById("hospitales_usuario2").length; i++) {
                        hospitales = hospitales + document.getElementById("hospitales_usuario2").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("grupos_usuario2").length; i++) {
                        grupos = grupos + document.getElementById("grupos_usuario2").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("categorias_usuario2").length; i++) {
                        categorias = categorias + document.getElementById("categorias_usuario2").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("ntf_tipos2").length; i++) {
                        ntf = ntf + document.getElementById("ntf_tipos2").options[i].value + "|";
                    }

                    formData = formData + "&hospitales=" + hospitales + "&grupos=" + grupos + "&categorias=" + categorias + "&ntf=" + ntf +"&opcion=alta_usuario";

                    console.log(formData);

                    $.ajax({
                        type: "POST",
                        url: "utils/funciones.php",
                        data: formData,
                        success: function (data) {
                            if (data.trim() === "1") {
                                hide_loader();
                                show_alert("Los datos se guardaron exitosamente", "alert-info");

                                $("#modal-duplicar-usuario").modal('hide');
                                $("#modal-duplicar-usuario").find('form')[0].reset();

                                $.ajax({
                                    url: "resources/tablas/tbl-usuarios.php",
                                    type: 'POST',
                                    success: function (data, textStatus, jqXHR) {
                                        $(".table_users").html(data);
                                    }
                                });
                                //$('#modal-duplicar-usuario').modal('toggle');;
                            } else {
                                hide_loader();
                                $("#add_erradduser2").html(data);
                            }
                        },
                        error: function () {
                            $("#add_erradduser2").html("Ocurrio un error al guardar");
                            hide_loader();
                        }
                    });
                } else {
                    $("#add_erradduser2").html("corriga los errores antes de guardar<br>");
                    hide_loader();
                }
            }
            function guardarDatos() {
                show_loader();
                var bandera = false;
                bandera = valida();

                if (bandera == false) {



                    $("#add_erradduser").html("");
                    var f = $("#frmUsuario");
                    var formData = f.serialize();

                    var hospitales = "";
                    var grupos = "";
                    var categorias = "";
                    var ntf = "";


                    for (i = 0; i < document.getElementById("hospitales_usuario").length; i++) {
                        hospitales = hospitales + document.getElementById("hospitales_usuario").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("grupos_usuario").length; i++) {
                        grupos = grupos + document.getElementById("grupos_usuario").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("categorias_usuario").length; i++) {
                        categorias = categorias + document.getElementById("categorias_usuario").options[i].value + "|";
                    }

                    for (i = 0; i < document.getElementById("ntf_tipos_usuario").length; i++) {
                        ntf = ntf + document.getElementById("ntf_tipos_usuario").options[i].value + "|";
                    }

                    formData = formData + "&hospitales=" + hospitales + "&grupos=" + grupos + "&categorias=" + categorias + "&ntf=" + ntf + "&opcion=alta_usuario";

                    console.log(formData);
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones.php",
                        data: formData,
                        success: function (data) {
                            if (data.trim() === "1") {
                                hide_loader();
                                show_alert("Los datos se guardaron exitosamente", "alert-info");

                                $("#modal-datos-usuario").modal('hide');
                                $("#modal-datos-usuario").find('form')[0].reset();

                                $.ajax({
                                    url: "resources/tablas/tbl-usuarios.php",
                                    type: 'POST',
                                    success: function (data, textStatus, jqXHR) {
                                        $(".table_users").html(data);
                                    }
                                });

                            } else {
                                hide_loader();
                                $("#add_erradduser").html(data);
                            }
                        },
                        error: function () {
                            $("#add_erradduser").html("Ocurrio un error al guardar");
                            hide_loader();
                        }
                    });
                } else {
                    $("#add_erradduser").html("corriga los errores antes de guardar<br>");
                    hide_loader();
                }
            }

            function resetpass() {
                $("#msjpass").html('');
                $("#divmodaldeleteheader").html('Reinicio de contraseñas');
                $("#divmodaldeletebody").html('¿Desea reiniciar el password del usuario ' + $("#nombre").val() + '?');
                $('#modal-confirm').find('.modal-footer #confirm').html('Reiniciar password');
                $("#modal-confirm").modal('show');
                $('#modal-confirm').find('.modal-footer #confirm').on('click', function () {
                    $.post("utils/funciones.php", {id_usuario: $("#id_usuario").val(), pass: $("#login").val(), opcion: "resetPass"}, function (respuesta) {
                        console.log(respuesta);
                        if (respuesta === "1") {
                            show_alert("El password fue reiniciado con exito", "alert-info");
                        } else {
                            show_alert(respuesta, "alert-danger");
                        }

                        setTimeout(function () {
                            $("#modal-confirm").modal('hide');
                        }, 3000);
                    });
                });
            }

            function limpiar() {
                $("#id_usuario").val("");
                $('input[name=login]').parent().removeClass("has-error");
                $('input[name=nombre]').parent().removeClass("has-error");
                $('select[name=rol]').parent().removeClass("has-error");
                $('select[name=grupo]').parent().removeClass("has-error");
                $('input[name=email]').parent().removeClass("has-error");
                $("#logindiv").html("");
                $("#nombrediv").html("");
                $("#emaildiv").html("");
                $("#puesto").html("");
                $("#roldiv").html("");
                $("#grupodiv").html("");
                $("#add_erradduser").html("");
            }
            function limpiar2() {
                $("#id_usuario2").val("");
                $('input[name=login]').parent().removeClass("has-error");
                $('input[name=nombre]').parent().removeClass("has-error");
                $('select[name=rol]').parent().removeClass("has-error");
                $('select[name=grupo]').parent().removeClass("has-error");
                $('input[name=email]').parent().removeClass("has-error");
                $("#logindiv2").html("");
                $("#nombrediv2").html("");
                $("#emaildiv2").html("");
                $("#puesto2").html("");
                $("#roldiv2").html("");
                $("#grupodiv2").html("");
                $("#add_erradduser2").html("");
            }

            function valida() {
                var errores = false;

                if ($("#nombre").val().length == 0) {
                    errores = true;
                    $('input[name=nombre]').parent().addClass("has-error");
                    $("#nombrediv").html("Debe introducir el nombre de empleado");
                }

                if ($("#login").val().length == 0) {
                    errores = true;
                    $('input[name=login]').parent().addClass("has-error");
                    $("#logindiv").html("Debe introducir el login");
                }

                if ($("#email").val().length == 0 && $("#notificaciones").is(':checked')) {
                    errores = true;
                    $('input[name=email]').parent().addClass("has-error");
                    $("#emaildiv").html("Debe introducir el email del empleado");
                }

                if ($("#rol").val() === "") {
                    errores = true;
                    $('select[name=rol]').parent().addClass("has-error");
                    $("#roldiv").html("Debe seleccionar el rol para el usuario");
                }

                return errores;
            }
            function validadup() {
                var errores = false;

                if ($("#nombre2").val().length == 0) {
                    errores = true;
                    $('input[name=nombre]').parent().addClass("has-error");
                    $("#nombrediv2").html("Debe introducir el nombre de empleado");
                }

                if ($("#login2").val().length == 0) {
                    errores = true;
                    $('input[name=login]').parent().addClass("has-error");
                    $("#logindiv2").html("Debe introducir el login");
                }

                if ($("#email2").val().length == 0 && $("#notificaciones2").is(':checked')) {
                    errores = true;
                    $('input[name=email]').parent().addClass("has-error");
                    $("#emaildiv2").html("Debe introducir el email del empleado");
                }

                if ($("#rol2").val() === "") {
                    errores = true;
                    $('select[name=rol]').parent().addClass("has-error");
                    $("#roldiv2").html("Debe seleccionar el rol para el usuario");
                }

                return errores;
            }
        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>


        <div class="content-wrapper">

            <div class="col-lg-12  col-md-12 " style="margin-bottom: 70px;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style=" padding-top: 0;">
                    <div class="row">
                        <h4 class="no-margin">Usuarios</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <form id="filtros">      
                                Búsqueda de usuarios
                                <div class="row">
                                    <div class="col-md-2 col-lg-2">                                                              
                                        <select class="form-control" id="cmbbuscahospitalpres" name="sltHospital">
                                            <?php consulta_hospitales_adm_usr(); ?>  
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2">                                                              
                                        <select class="form-control" id="cmbbuscaclasificacion" name="sltRol">
                                            <?php consulta_roles_adm_usr(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2">                                                              
                                        <input class="form-control" id="txtDescripcion" name="txtVarios" type="text" data-toggle="tooltip" title="Busca por login, nombre o email" onkeydown="if (event.keyCode == 13) {
                                                    return false;
                                                }"/>
                                    </div>
                                    <div class="col-md-2 col-lg-2"> 
                                        <button type="button" class="btn btn-link" id="consultarUsr"  style="margin: -5px 0 0 0;">
                                            <span class="glyphicon glyphicon-search"></span> CONSULTAR
                                        </button>                      
                                    </div>
                                    <div class="col-md-2 col-lg-2"> 
                                        <button type="button"  class="btn btn-primary pull-right" onclick="javascript:duplicarUsuario()">
                                            <span class="fa fa-users" aria-hidden="true" ></span> Duplicar Usuario
                                        </button>
                                    </div>
                                    <div class="col-md-2 col-lg-2"> 
                                        <button type="button"  class="btn btn-primary pull-right" onclick="javascript:nuevousuario()">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span> Agregar Usuario
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">

                    <div class="table_users">

                    </div>

                </div> 
            </div>

        </div>

        <?php include 'resources/modals/modal-datos-usuario.php'; ?>
        <?php include 'resources/modals/modal-confirm.php'; ?>
        <?php include 'resources/modals/modal-duplicar-usuario.php'; ?>
        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>