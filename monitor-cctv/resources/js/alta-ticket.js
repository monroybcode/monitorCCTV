var saved = true;

window.onbeforeunload = function () {
    if (saved == true) {
        return "Tienes cambios pendientes, deseas salir?";
    }
};


$(document).ready(function () {
    $("#archivos_ticket").change(function (e) {
        $(".files-names").html('');

        var total_archivos = this.files.length;
        $(".num_archivos_sel").html(total_archivos);

        if (total_archivos === 1) {
            $(".num_archivos_sel").html(total_archivos + " archivo seleccionado");
        } else if (total_archivos > 1) {
            $(".num_archivos_sel").html(total_archivos + " archivos seleccionados");
        } else {
            $(".num_archivos_sel").html("Ningun archivo seleccionado");
        }

        for (var i = 0; i < total_archivos; i++) {
            $(".files-names").append(this.files[i].name + '<br>');
        }

    });

    //<editor-fold defaultstate="collapesed" desc="******** funciones alta anterior ********">

//    $("#categoria_1").change(function (e) {
//        var data = "id_categoria=" + $("#categoria_1").val() + "&opcion=consulta_subcategoria&subcategoria=2";
//
//        $("#categoria_2").hide();
//        $("#categoria_3").hide();
//        $("#categoria_4").hide();
//
//        $.ajax({
//            url: "utils/funciones.php",
//            type: 'POST',
//            data: data,
//            dataType: 'json',
//            success: function (data, textStatus, jqXHR) {
//                if (Number(data.num_resultados) > 0) {
//                    $("#categoria_2").show();
//                    $("#categoria_2").html('');
//                    $("#categoria_2").append(data.opciones);
//                }
//
//            },
//            error: function (jqXHR, textStatus, errorThrown) {
//                console.log("error");
//            }
//        });
//    });
//
//    $("#categoria_2").change(function (e) {
//        var data = "id_categoria=" + $("#categoria_2").val() + "&opcion=consulta_subcategoria&subcategoria=3";
//
//        $("#categoria_3").hide();
//        $("#categoria_4").hide();
//
//        $.ajax({
//            url: "utils/funciones.php",
//            type: 'POST',
//            data: data,
//            dataType: 'json',
//            success: function (data, textStatus, jqXHR) {
//
//                if (Number(data.num_resultados) > 0) {
//                    $("#categoria_3").show();
//                    $("#categoria_3").html('');
//                    $("#categoria_3").append(data.opciones);
//                }
//
//            },
//            error: function (jqXHR, textStatus, errorThrown) {
//                console.log("error");
//            }
//        });
//    });
//
//    $("#categoria_3").change(function (e) {
//        var data = "id_categoria=" + $("#categoria_3").val() + "&opcion=consulta_subcategoria&subcategoria=4";
//
//        $("#categoria_4").hide();
//
//        $.ajax({
//            url: "utils/funciones.php",
//            type: 'POST',
//            data: data,
//            dataType: 'json',
//            success: function (data, textStatus, jqXHR) {
//                if (Number(data.num_resultados) > 0) {
//                    $("#categoria_4").show();
//                    $("#categoria_4").html('');
//                    $("#categoria_4").append(data.opciones);
//                }
//
//            },
//            error: function (jqXHR, textStatus, errorThrown) {
//                console.log("error");
//            }
//        });
//    });
//
//    $("#btn-guarda-ticket").click(function (e) {
//        $("#btn-guarda-ticket").prop("disabled", true);
//        show_loader();
//        e.preventDefault();
//
//        var faltan_campos = "no";
//
//        if ($("#unidad_organizacional").val() === "") {
//            faltan_campos = "si";
//        }
//
//        if ($("#horario_contacto").val() === "") {
//            faltan_campos = "si";
//        }
//
//        if (faltan_campos === "si") {
//            hide_loader();
//            show_alert("Completa todos los campos", "alert-danger");
//            $("#btn-guarda-ticket").prop("disabled", false);
//        } else {
//
//            $("#nombre_contacto").prop("disabled", false);
//            $("#unidad_organizacional").prop("disabled", false);
//
//            var formData = new FormData(document.getElementById("form-alta-ticket"));
//
//            $("#nombre_contacto").prop("disabled", true);
//
//            $.ajax({
//                url: "resources/controller/controller-ticket.php",
//                type: 'POST',
//                data: formData,
//                dataType: 'json',
//                cache: false,
//                processData: false,
//                contentType: false,
//                success: function (data, textStatus, jqXHR) {
//                    console.log(data);
//                    if (data.correcto == true) {
//                        saved = false;
//                        hide_loader();
//                        swal.queue([{
//                                title: '',
//                                confirmButtonText: 'ok',
//                                type: 'success',
//                                text: 'Ticket creado con el número de folio: ' + data.folio,
//                                showLoaderOnConfirm: true,
//                                allowOutsideClick: false,
//                                preConfirm: function () {
//                                    window.location.href = "tickets-preview.php?nv=1";
//                                }
//                            }]);
//                    } else {
//                        $("#btn-guarda-ticket").prop("disabled", false);
//                        hide_loader();
//                        swal({
//                            title: '',
//                            confirmButtonText: 'ok',
//                            type: 'error',
//                            text: 'Ocurrio un error al guardar el ticket',
//                            showLoaderOnConfirm: true,
//                            allowOutsideClick: false
//                        });
//                    }
//                },
//                error: function (jqXHR, textStatus, errorThrown) {
//                    console.log(textStatus);
//                    $("#btn-guarda-ticket").prop("disabled", false);
//                    hide_loader();
//                    swal({
//                        title: '',
//                        confirmButtonText: 'ok',
//                        type: 'error',
//                        text: 'Ocurrio un error',
//                        showLoaderOnConfirm: true,
//                        allowOutsideClick: false
//                    });
//                }
//
//            });
//
//        }
//
//    });

    //</editor-fold>

    $("#categoria_2").change(function (e) {
        var data = "id_categoria=" + $("#categoria_2").val() + "&opcion=consulta_categoria_padre";
        $.ajax({
            url: "utils/funciones.php",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                $("#categoria_1").val(data.id);
                $(".nombre-categoria-1").html(data.name);
                $("#grupo").val(data.gpo_categ);
                $("#categoria_3").html('<option value=""> - Seleccione tipo de operación - </option>');

                if (data.desc_ayuda != "") {
                    $('#spnInfoAyudaT').prop('title', data.desc_ayuda);
                    $('#spnInfoAyudaT').show();
                } else {
                    $('#spnInfoAyudaT').hide();
                }
                if (data.url_formatos != "" && $("#categoria_2").val() == 16) {
                    $('#url_formatos').show();
                    $('#url_formatos').attr('href', data.url_formatos)
                } else {
                    $('#url_formatos').hide();
                }


                if (data.tot_hijos > 0) {
                    $("#categoria_3").show();
                    $.ajax({
                        url: "utils/funciones.php",
                        type: 'POST',
                        data: "id_categoria=" + $("#categoria_2").val() + "&opcion=consulta_categorias_hijo",
                        success: function (data) {
                            $("#categoria_3").append(data);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("error");
                        }
                    });

                } else {
                    $("#categoria_3").hide();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("error");
            }
        });
    });

    $("#categoria_3").change(function (e) {
        var data = "id_categoria=" + $("#categoria_3").val() + "&opcion=consulta_subcategoria&subcategoria=4";

        $("#categoria_4").hide();

        $.ajax({
            url: "utils/funciones.php",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (Number(data.num_resultados) > 0) {
                    $("#categoria_4").show();
                    $("#categoria_4").html('');
                    $("#categoria_4").append(data.opciones);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("error");
            }
        });
    });


    $("#btn-crear-ticket").click(function (e) {
        console.log("hola");

        var asignado = '';
        var copias = '';

        $("input[name='hidden-tags']").each(function () {
            asignado = $(this).val();

        });

        $("input[name='hidden-tags2']").each(function () {
            copias = $(this).val();
        });
        console.log(asignado);
        console.log(copias);




        var crear = 1;

        if ($("#comentarios").val() === '' || $("#comentarios").val().length < 20) {

            crear = 2;
        }


        if ($("#unidad_organizacional").val() !== ''
                && $("#area").val() !== ''
                && $("#evento").val() !== ''
                && $("#categoria").val() !== ''
                && $("#prioridad").val() !== ''
                && $("#url").val() !== ''
                && $("#comentarios").val() !== ''
                && asignado !== ''
                && copias !== ''

                ) {


            crear = 0;
        }

        if (crear !== 1 && crear !== 2)
        {
            $("#btn-crear-ticket").prop("disabled", true);
            show_loader();
            e.preventDefault();


            $("#nombre_contacto").prop("disabled", false);
            $("#Email").prop("disabled", false);
            $("#unidad_organizacional").prop("disabled", false);

            var formData = new FormData(document.getElementById("form-alta-ticket"));

            $("#nombre_contacto").prop("disabled", true);

            $.ajax({
                url: "resources/controller/controller-ticket.php",
                type: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.correcto == true) {
                        saved = false;
                        hide_loader();
                        swal.queue([{
                                title: '',
                                confirmButtonText: 'ok',
                                type: 'success',
                                text: 'Reporte creado con el número de folio: ' + data.folio,
                                showLoaderOnConfirm: true,
                                allowOutsideClick: false,
                                preConfirm: function () {
                                    window.location.href = "tickets-preview.php?nv=1";
                                }
                            }]);
                    } else {
                        $("#btn-crear-ticket").prop("disabled", false);
                        $("#btn-guarda-ticket").prop("disabled", false);
                        hide_loader();
                        swal({
                            title: '',
                            confirmButtonText: 'ok',
                            type: 'error',
                            text: 'Ocurrio un error al guardar el ticket',
                            showLoaderOnConfirm: true,
                            allowOutsideClick: false
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    $("#btn-crear-ticket").prop("disabled", false);
                    $("#btn-guarda-ticket").prop("disabled", false);
                    hide_loader();
                    swal({
                        title: '',
                        confirmButtonText: 'ok',
                        type: 'error',
                        text: 'Ocurrio un error',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false
                    });
                }

            });



        } else
        if (crear === 2)
        {
            swal("Error!", "<p style='text-align:justify;'>El campo Comentarios es Obligatorio y el minimo es de 20 caracteres</p>", "error");
        } else
        {

            swal("Error!", "<p style='text-align:justify;'>Todos los Campos son Obligatorios: <br>\n\
                <br>- Unidad Hospitalaria \n\
                <br>- Area \n\
                <br>- Reporte \n\
                <br>- Categoria \n\
                <br>- Prioridad\n\
                <br>- Inicio Evento \n\
                <br>- Termino Evento \n\
                <br>- Datos Adicionales \n\
                <br>- URL \n\
                <br>- Asignacion </p>\n\
              ", "error");

        }


    });


    disabled_btn_guardar();

});

function disabled_btn_guardar() {
    if ($("#unidad_organizacional").val() === "0" || $("#grupo").val() === "0") {
        $("#btn-guarda-ticket").hide();
        $("#btn-crear-ticket").hide();
    }
}

function consultaNumTel(hosp) {
    $.ajax({
        url: "utils/funciones.php",
        type: 'POST',
        data: "opcion=consulta_telefono_hospital&id_hospital=" + hosp,
        success: function (data, textStatus, jqXHR) {
            $("#telefono_contacto").val(data);
        }
    });
}

//<editor-fold defaultstate="collapsed" desc="******* funciones alta ticket anterior ************">
//function panel1() {
//    $("#t-step1").addClass("active");
//    $("#step1").addClass("active");
//
//    $("#t-step2").removeClass("active");
//    $("#step2").removeClass("active");
//}
//
//function panel2() {
//    var faltan_campos = "no";
//
//    if ($("#categoria_1").val() === "") {
//        faltan_campos = "si";
//    }
//
//    if ($("#categoria_2").css("display") === "block" && $("#categoria_2").val() === "") {
//        faltan_campos = "si";
//    }
//
//    if ($("#categoria_3").css("display") === "block" && $("#categoria_3").val() === "") {
//        faltan_campos = "si";
//    }
//
//    if ($("#categoria_4").css("display") === "block" && $("#categoria_4").val() === "") {
//        faltan_campos = "si";
//    }
//
//    if (faltan_campos === "si") {
//        show_alert("Completa todos los campos", "alert-danger");
//    } else {
//        $("#t-step2").addClass("active");
//        $("#step2").addClass("active");
//
//        $("#t-step1").removeClass("active");
//        $("#step1").removeClass("active");
//
//        $("#t-step3").removeClass("active");
//        $("#step3").removeClass("active");
//    }
//}
//
//function panel3() {
//var faltan_campos = "no";
//
//if ($("#comentarios").val() === "") {
//    faltan_campos = "si";
//}
//
//if (faltan_campos === "si") {
//    show_alert("Comentario obligatorio", "alert-danger");
//} else {
//    $("#t-step3").addClass("active");
//    $("#step3").addClass("active");
//    $("#t-step2").removeClass("active");
//    $("#step2").removeClass("active");
//}
//}
//</editor-fold>

function formulario_incompleto() {
    var faltan_campos = false;

    if ($("#categoria_1").val() === "") {
        faltan_campos = true;
    }

    if ($("#categoria_2").css("display") === "block" && $("#categoria_2").val() === "") {
        faltan_campos = true;
    }

    if ($("#categoria_3").css("display") === "block" && $("#categoria_3").val() === "") {
        faltan_campos = true;
    }

    if ($("#categoria_4").css("display") === "block" && $("#categoria_4").val() === "") {
        faltan_campos = true;
    }

    if ($("#comentarios").val() === "") {
        faltan_campos = true;
    }

    if ($("#unidad_organizacional").val() === "") {
        faltan_campos = true;
    }

    if ($("#grupo").val() === "") {
        faltan_campos = true;
    }

    if ($("#horario_contacto").val() === "") {
        faltan_campos = true;
    }


    return faltan_campos;
}

function show_loader() {
    $(".loader").show();
}

function hide_loader() {
    $(".loader").hide();
}

function show_alert(msg, type) {
    $("#alert").addClass(type);
    $("#txt-alert").html(msg);

    $("#alert").fadeIn(1000);

    setTimeout(function () {
        $("#alert").fadeOut(1000);
        $("#alert").removeClass(type);
    }, 3000);
}

