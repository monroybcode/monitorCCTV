window.history.forward(1);
$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $("#tab-ejecutivos").click(function (e) {
        if ($("#report-option").val() === "") {
            show_alert("Selecciona el tipo de reporte", "alert-danger");
        } else {
            show_loader();
            if ($("#report-option").val() === "Sin asignar") {
                $.ajax({
                    url: "resources/tablas/tbl-tickets-sin-asignar.php",
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#cont-ejecutivos").html(data);
                        $(function () {
                            $("#accordion").accordion({
                                collapsible: true
                            });
                        });
                        hide_loader();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        hide_loader();
                    }
                });
            } else {
                $.ajax({
                    url: "resources/tablas/tbl-tickets-usuarios.php",
                    type: 'POST',
                    data: "restriccion=" + $("#report-restriccion").val(),
                    success: function (data, textStatus, jqXHR) {
                        $("#cont-ejecutivos").html(data);
                        $(function () {
                            $("#accordion").accordion({
                                collapsible: true
                            });
                        });
                        hide_loader();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        hide_loader();
                    }
                });
            }


        }
    });
    $("#btn-aplicar-editar-nota").click(function (e) {
        e.preventDefault();
        show_loader();
        $.ajax({
            url: 'resources/controller/controller-edita-nota.php',
            type: 'POST',
            data: $("#frm-edita-nota").serialize() + "&id_ticket=" + $("#id_ticket").val(),
            success: function (data) {
                if (data === '1') {
                    hide_loader();
                    show_alert("Se actualizó nota", "alert-info");
                    $(".texto-nota-" + $("#id_nota_hdn").val()).html($(".txt-nota-editar").val().replace(/\n/gi, "<br>"));
                } else {
                    hide_loader();
                    show_alert("Ocurrio un error", "alert-danger");
                }
            },
        });
        $("#modal-edita-nota").modal('hide');
    });
    $("#btn-aplicar").click(function (e) {
        e.preventDefault();

        show_loader();
      
        if ($(".txt-nota-tratar").val().length < 25) {

            show_alert("Texto de nota requerido minimo 25 caracteres", "alert-danger");
            hide_loader();
        } else {
            $("#btn-aplicar").prop("disabled", true);
            var respuesta = registra_nota_archivo("frm-tratar-ticket");
            $("#mdl-tratar-ticket").modal('hide');
            saved = false;

            if (respuesta === "correcto") {
                show_alert("Guardado", "alert-info");
            } else {
                show_alert("Ocurrio un error al guardar", "alert-danger");
            }
            $("#btn-aplicar").prop("disabled", false);
        }
    });

    $("#btn-recategorizar").click(function (e) {
        e.preventDefault();
        $("#btn-recategorizar").prop("disabled", true);
        show_loader();


        $.ajax({
            url: 'resources/controller/recategorizar.php',
            type: 'POST',
            data: $("#frm-recategorizar-ticket").serialize() + "&id_ticket=" + $("#id_ticket").val(),
            success: function (data) {
                $("#mdl-recategorizar-ticket").modal('hide');
                show_alert("Clasificado", "alert-info");

                window.setTimeout(function () {

                    // Move to a new location or you can do something else
                    window.location.href = "tickets-preview.php";

                }, 3000);



                hide_loader();

                $("#btn-recategorizar").prop("disabled", false);
            }
        });


    });




    $(".btn-autoriza-ticket").click(function (e) {
        $(".title-op").html("Autorizar");
        $("#operacion").val("Autorizar");
        $("#mdl-aut-rech-ticket").modal('show');
    });
    $(".btn-rechaza-ticket").click(function (e) {
        $(".title-op").html("Rechazar");
        $("#operacion").val("Rechazar");
        $("#mdl-aut-rech-ticket").modal('show');
    });
    $("#btn-aplica-rech-aut").click(function (e) {
        e.preventDefault();
        if ($('.txt-nota-aut-rech').val() === "") {
            hide_loader();
            show_alert("Texto de nota requerido", "alert-danger");
        } else {

            if ($("#operacion").val() === "Rechazar") {
                swal({
                    type: "warning",
                    title: 'Estas seguro',
                    text: "que deseas rechazar?",
                    confirmButtonText: 'Rechazar',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                }).then(function () {
                    envia_aut_rech("Rechazada");
                });
            } else {
                envia_aut_rech("Autorizada");
            }
            $("#mdl-aut-rech-ticket").modal('hide');
        }
    });
    $("#btn-asigna-operador").click(function (e) {
        e.preventDefault();
        $("#btn-asigna-operador").prop("disabled", true);
        show_loader();
//        if ($("#operador").val() === "" || $('.txt-nota-asignar').val() === "") {
//            hide_loader();
//            show_alert("Complete los campos", "alert-danger");
//        } else {

        var operador_combo = document.getElementById("operador");
        var operador_selected = operador_combo.options[operador_combo.selectedIndex].text;
        $.ajax({
            url: 'resources/controller/asignar_ticket.php',
            type: 'POST',
            data: $("#frm-asigna-operador").serialize() + "&id_ticket=" + $("#id_ticket").val(),
            success: function (data) {
                if (data === "asignado") {
                    $("#usuario_asignado").html(operador_selected);
                    registra_nota_archivo("frm-asigna-operador");
                    saved = false;
                    show_alert("Asignado", "alert-info");
                    location.reload();
                } else {
                    show_alert(data, "alert-danger");
                }
            }
        });
        $("#mdl-asignar").modal('hide');
        $("#btn-asigna-operador").prop("disabled", false);
//        }
    });
    $("#btn-solicita-autorizacion").click(function (e) {
        show_loader();
        e.preventDefault();
        if ($("#autorizador").val() === "") {
            hide_loader();
            show_alert("Selecciona el usuario", "alert-danger");
        } else {
            $.ajax({
                url: 'resources/controller/solicita-autorizacion.php',
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                }
            });
            registra_nota_archivo("frm-solicita-autorizacion");
            saved = false;
            $("#mdl-solicita-autorizacion").modal('hide');
        }
    });
    $("#btn-procesa-solicitud").click(function (e) {
        show_loader();
        e.preventDefault();
        if ($(".txt-procesar-ticket").val() === "") {
            hide_loader();
            show_alert("Texto de nota requerido", "alert-danger");
        } else {
            $.ajax({
                url: 'resources/controller/controller-procesar-ticket.php',
                type: 'POST',
                data: $("#frm-procesa-ticket").serialize() + "&id_ticket=" + $("#id_ticket").val(),
                success: function (data, textStatus, jqXHR) {
                    if (data === "procesado") {
                        registra_nota_archivo("frm-procesa-ticket");
                        show_alert("Procesada", "alert-info");
                        saved = false;
                        setTimeout(function () {
                            window.location.href = "tickets-preview.php";
                        }, 1500);
                    } else {
                        show_alert(data, "alert-danger");
                    }
                }
            });
            $("#mdl-procesar-ticket").modal('hide');
        }
    });
    $("#btn-resolver-ticket").click(function (e) {
        if ($("#txt-note-resolver").val().length > 25) {
            show_loader();
            e.preventDefault();
            $("#btn-resolver-ticket").prop("disabled", true);

            $.ajax({
                url: 'resources/controller/controller-resolver-ticket.php',
                type: 'POST',
                data: $("#frm-resolver-ticket").serialize() + "&id_ticket=" + $("#id_ticket").val(),
                success: function (data, textStatus, jqXHR) {
                    if (data === "resuelta") {
                        registra_nota_archivo("frm-resolver-ticket");
                        show_alert("Resuelto", "alert-info");
                        saved = false;
                        setTimeout(function () {
                            window.location.href = "tickets-preview.php";
                        }, 1500);
                    } else {
                        show_alert(data, "alert-danger");
                    }
                }
            });
            $("#mdl-resolver-ticket").modal('hide');
            $("#btn-resolver-ticket").prop("disabled", false);
        } else {
            show_alert("Texto de nota requerido minimo 25 caracteres", "alert-danger");
        }
//        }
    });
    $("#btn-cancela-ticket").click(function (e) {
        e.preventDefault();
        $("#btn-cancela-ticket").prop("disabled", true);
        if ($(".txt-nota-cancelar").val() === "") {
            show_alert("Motivo de cancelación requerido", "alert-danger");
        } else {

            $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
            $("#divmodaldeleteheader").html("Confirmar cancelación");
            $("#divmodaldeletebody").html("Seguro que deseas cancelar el ticket?");
            $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
            $("#modal-confirm").modal('show');
            $("#modal-confirm").find(".modal-footer #confirm").click(function () {
                show_loader();
                $("#modal-confirm").modal('hide');
                $.ajax({
                    url: 'resources/controller/controller-cancelar-ticket.php',
                    type: 'POST',
                    data: $("#frm-cancelar-ticket").serialize() + "&id_ticket=" + $("#id_ticket").val(),
                    success: function (data, textStatus, jqXHR) {
                        if (data === "cancelada") {
                            registra_nota_archivo("frm-cancelar-ticket");
                            show_alert("Cancelada", "alert-info");
                            saved = false;
                            setTimeout(function () {
                                window.location.href = "tickets-preview.php";
                            }, 1500);
                        } else {
                            show_alert(data, "alert-danger");
                        }

                        $("#mdl-cancelar-ticket").modal('hide');
                    }
                });
            });
        }
        $("#btn-cancela-ticket").prop("disabled", false);
    });
    $("#btn-cerrar-ticket").click(function (e) {
        e.preventDefault();
        $("#btn-cerrar-ticket").prop("disabled", true);
        //if ($(".txt-nota-cerrar").val() === "") {
        //    show_alert("Texto de nota requerido", "alert-danger");
        //} else {
        $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
        $("#divmodaldeleteheader").html("Confirmar cierre");
        $("#divmodaldeletebody").html("Seguro que deseas cerrar el ticket?");
        $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
        $("#modal-confirm").modal('show');
        $("#modal-confirm").find(".modal-footer #confirm").click(function () {
            show_loader();
            $("#modal-confirm").modal('hide');
            regresar_cerrar_ticket_f("cerrado", "frm-cerrar-ticket");
        });
        $("#btn-cerrar-ticket").prop("disabled", false);
        //}
    });
    $("#btn-regresar-ticket").click(function (e) {
        e.preventDefault();
        if ($(".txt-nota-reabrir").val() === "") {
            show_alert("Texto de nota requerido", "alert-danger");
        } else {
            $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
            $("#divmodaldeleteheader").html("Confirmar reabrir ticket");
            $("#divmodaldeletebody").html("Seguro que deseas reabrir el ticket?");
            $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
            $("#modal-confirm").modal('show');
            $("#modal-confirm").find(".modal-footer #confirm").click(function () {
                show_loader();
                $("#modal-confirm").modal('hide');
                regresar_cerrar_ticket_f("reabierto", "frm-reabir-ticket");
            });
        }
    });

    //Logica para avanzar de etapa en flujo de tickets
    $("#btn-fp-resolver-ticket").click(function (e) {
        show_loader();
        e.preventDefault();
        $("#btn-fp-resolver-ticket").prop("disabled", true);

        $.ajax({
            url: 'resources/controller/controller-fp-resolver-ticket.php',
            type: 'POST',
            data: "id_ticket=" + $("#id_ticket").val() + "&sig_etepa_fp=" + $("#hdnEtapaPosFP").val(),
            success: function (data) {
                if (data === "resuelta") {
                    //registra_nota_archivo("frm-fp-resolver-ticket");
                    show_alert("Atendido", "alert-info");
                    saved = false;
                    setTimeout(function () {
                        window.location.href = "tickets-preview.php";
                    }, 2000);
                } else {
                    show_alert(data, "alert-danger");
                }
            }
        });
        //$("#mdl-fp-resolver-ticket").modal('hide');
        //$("#btn-fp-resolver-ticket").prop("disabled", false);
    });

    //Logica para regresar de etapa en flujo de tickets
    $("#btn-fp-regresar-ticket").click(function (e) {
        show_loader();
        e.preventDefault();
        $("#btn-fp-regresar-ticket").prop("disabled", true);

        $.ajax({
            url: 'resources/controller/controller-fp-regresar-ticket.php',
            type: 'POST',
            data: "id_ticket=" + $("#id_ticket").val() + "&sig_etepa_fp=" + $("#hdnEtapaAntFP").val(),
            success: function (data) {
                if (data === "resuelta") {
                    registra_nota_archivo("frm-fp-regresar-ticket");
                    show_alert("Atendido", "alert-info");
                    saved = false;
                    setTimeout(function () {
                        window.location.href = "tickets-preview.php";
                    }, 2000);
                } else {
                    show_alert(data, "alert-danger");
                }
            }
        });
        $("#mdl-fp-regresar-ticket").modal('hide');
        $("#btn-fp-regresar-ticket").prop("disabled", false);
    });

    //Logica para autorizar etapa en flujo de tickets
    $("#btn-fp-autorizar-ticket").click(function (e) {
        show_loader();
        e.preventDefault();
        $("#btn-fp-autorizar-ticket").prop("disabled", true);

        $.ajax({
            url: 'resources/controller/controller-fp-autorizar-ticket.php',
            type: 'POST',
            data: "id_ticket=" + $("#id_ticket").val() + "&sig_etepa_fp=" + $("#hdnEtapaPosFP").val(),
            success: function (data) {
                if (data === "resuelta") {
                    //registra_nota_archivo("frm-fp-autorizar-ticket");
                    show_alert("Atendido", "alert-info");
                    saved = false;
                    setTimeout(function () {
                        window.location.href = "tickets-preview.php";
                    }, 2000);
                } else {
                    show_alert(data, "alert-danger");
                }
            }
        });
        //$("#mdl-fp-autorizar-ticket").modal('hide');
        //$("#btn-fp-autorizar-ticket").prop("disabled", false);
    });

    //Logica para rechazar de etapa en flujo de tickets
    $("#btn-fp-rechazar-ticket").click(function (e) {
        show_loader();
        e.preventDefault();
        $("#btn-fp-rechazar-ticket").prop("disabled", true);

        $.ajax({
            url: 'resources/controller/controller-fp-rechazar-ticket.php',
            type: 'POST',
            data: "id_ticket=" + $("#id_ticket").val() + "&sig_etepa_fp=" + $("#hdnEtapaAntFP").val(),
            success: function (data) {
                if (data === "resuelta") {
                    registra_nota_archivo("frm-fp-rechazar-ticket");
                    show_alert("Atendido", "alert-info");
                    saved = false;
                    setTimeout(function () {
                        window.location.href = "tickets-preview.php";
                    }, 2000);
                } else {
                    show_alert(data, "alert-danger");
                }
            }
        });
        $("#mdl-fp-rechazar-ticket").modal('hide');
        $("#btn-fp-rechazar-ticket").prop("disabled", false);
    });

    $("#btn-fp-rechazar-c-ticket").click(function (e) {
        e.preventDefault();
        $("#btn-fp-rechazar-c-ticket").prop("disabled", true);
        $("#mdl-fp-rechazar-c-ticket").modal('hide');
        //if ($(".txt-nota-cerrar").val() === "") {
        //    show_alert("Texto de nota requerido", "alert-danger");
        //} else {
        $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
        $("#divmodaldeleteheader").html("Confirmar rechazo");
        $("#divmodaldeletebody").html("¿Seguro que deseas rechazar el ticket?");
        $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
        $("#modal-confirm").modal('show');
        $("#modal-confirm").find(".modal-footer #confirm").click(function () {
            show_loader();
            $("#modal-confirm").modal('hide');
            regresar_cerrar_ticket_f("cerrado", "frm-fp-rechazar-c-ticket");
        });
        $("#btn-fp-rechazar-c-ticket").prop("disabled", false);
        //}
    });

    $(".cancelar").click(function (e) {
        saved = false;
    });
});

function show_report_resumen(reporte, na) {
    window.location.href = "reporte.php?report=" + reporte + "&name=" + na;
}

function baja_nota(id_nota) {
    $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
    $("#divmodaldeleteheader").html('Confirmar baja nota');
    $("#divmodaldeletebody").html('Deseas dar de baja la nota?');
    $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
    $("#modal-confirm").modal('show');
    $("#modal-confirm").find(".modal-footer #confirm").click(function (e) {
        show_loader();
        $.ajax({
            url: 'resources/controller/baja-nota.php',
            data: "opcion=" + "baja_nota" + "&id_ticket=" + $("#id_ticket").val() + "&id_nota=" + id_nota,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                if (data === "1") {
                    hide_loader();
                    show_alert("Se dio de baja la nota", "alert-info");
                    $("#modal-confirm").modal('hide');
                    $("#div_post_" + id_nota).remove();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
}


function baja_archivo(id_archivo) {
    $("#modal-confirm").find(".modal-footer #confirm").unbind('click');
    $("#divmodaldeleteheader").html('Confirmar baja adjunto');
    $("#divmodaldeletebody").html('Deseas dar de baja el documento adjunto?');
    $("#modal-confirm").find(".modal-footer #confirm").html('Confirmar');
    $("#modal-confirm").modal('show');
    $("#modal-confirm").find(".modal-footer #confirm").click(function (e) {
        show_loader();
        $.ajax({
            url: 'resources/controller/baja-adjunto.php',
            data: "id_ticket=" + $("#id_ticket").val() + "&id_documento=" + id_archivo,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                if (data === "1") {
                    hide_loader();
                    show_alert("Se dio de baja el documento adjunto", "alert-info");
                    $("#modal-confirm").modal('hide');
                    $("#div_post_adjunto_" + id_archivo).remove();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
}



function edita_nota(id_nota) {
    $("#id_nota_hdn").val(id_nota);
    $(".txt-nota-editar").val($(".texto-nota-" + id_nota).html().replace(/<br>/gi, ""));
    $("#modal-edita-nota").modal('show');
}

function regresar_cerrar_ticket_f(operacion, form) {
    $.ajax({
        url: 'resources/controller/controller-cerrar-ticket.php',
        type: 'POST',
        data: $("#" + form).serialize() + "&id_ticket=" + $("#id_ticket").val() + "&operacion=" + operacion,
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            if (data === "1") {
                registra_nota_archivo(form);
                show_alert("Ticket " + operacion, "alert-info");
                saved = false;
                setTimeout(function () {
                    window.location.href = "tickets-preview.php";
                }, 1500);
            } else {
                show_alert(data, "alert-danger");
            }

            $("#mdl-cerrar-ticket").modal('hide');
        }
    });
}

function abre_modal_cancelar_ticket() {
    $("#frm-cancelar-ticket")[0].reset();
    $("#mdl-cancelar-ticket").modal('show');
}

function abre_modal_cerrar_ticket() {
    $("#frm-cerrar-ticket")[0].reset();
    $("#mdl-cerrar-ticket").modal('show');
}

function abre_modal_reabrir_ticket() {
    consulta_usuarios_modal_asignar("consulta_admin_des", $("#id_ticket").val());
    $("#frm-reabir-ticket")[0].reset();
    $("#mdl-reabrir-ticket").modal('show');
}

function abre_modal_asignar() {
    consulta_usuarios_modal_asignar("consulta_admin_des", $("#id_ticket").val());
    $("#frm-asigna-operador")[0].reset();
//    $("#stts_asignar_operador").val("4");
    $("#mdl-asignar").modal('show');
}

function abre_modal_procesar_ticket() {
    $("#frm-procesa-ticket")[0].reset();
    $("#mdl-procesar-ticket").modal('show');
}

function abre_modal_nota() {
    $("#frm-tratar-ticket")[0].reset();
    $("#mdl-tratar-ticket").modal('show');
}

function recategorizar() {
    $("#frm-recategorizar-ticket")[0].reset();
    $("#mdl-recategorizar-ticket").modal('show');
}

function abre_modal_resolver_ticket() {
    $("#frm-resolver-ticket")[0].reset();
    $("#mdl-resolver-ticket").modal('show');
}

function abrirModalFPResolver() {
    $("#frm-fp-resolver-ticket")[0].reset();
    $("#mdl-fp-resolver-ticket").modal('show');
}

function abrirModalFPRegresar() {
    $("#frm-fp-regresar-ticket")[0].reset();
    $("#mdl-fp-regresar-ticket").modal('show');
}

function abrirModalFPAutorizar() {
    $("#frm-fp-autorizar-ticket")[0].reset();
    $("#mdl-fp-autorizar-ticket").modal('show');
}

function abrirModalFPRechazar() {
    $("#frm-fp-rechazar-ticket")[0].reset();
    $("#mdl-fp-rechazar-ticket").modal('show');
}

function abrirModalFPRechazarYCierre() {
    $("#frm-fp-rechazar-c-ticket")[0].reset();
    $("#mdl-fp-rechazar-c-ticket").modal('show');
}

function envia_aut_rech(op) {
    show_loader();
    $.ajax({
        url: 'resources/controller/controller-autoriza-rechaza-ticket.php',
        type: 'POST',
        data: $("#frm-aut-rech-ticket").serialize() + "&id_ticket=" + $("#id_ticket").val(),
        success: function (data) {
            if (data === "1") {
                registra_nota_archivo("frm-aut-rech-ticket");
                show_alert(op, "alert-info");
                saved = false;
                setTimeout(function () {
                    window.location.href = "tickets-preview.php";
                }, 1500);
            } else {
                show_alert(data, "alert-danger");
            }
        }
    });
}

function consulta_usuarios_modal_asignar(consulta, id_ticket) {
    $.ajax({
        url: 'utils/funciones.php',
        data: "opcion=" + consulta + "&id_ticket=" + id_ticket,
        type: 'POST',
        success: function (data, textStatus, jqXHR) {
            //console.log("operador: "+ data);
            $("#operador2").html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });
}

function desmarcar(id) {
    $("#" + id).prop('checked', false);
    if ($("#informacion").prop('checked')) {
        consulta_usuarios_modal_asignar("consulta_usuario_registra", $("#id_ticket").val());
        $("#stts_asignar_operador").val("2");
    } else if ($("#autorizacion").prop('checked')) {
        consulta_usuarios_modal_asignar("consulta_actualizador");
        $("#stts_asignar_operador").val("3");
    } else {
        consulta_usuarios_modal_asignar("consulta_admin_des");
        $("#stts_asignar_operador").val("4");
    }
}

function getDimension() {
    var head = $(".header-title-content").height();
    var cw = $(".content-wrapper").height();
    var pd = $(".panel-detail").height();
    var sb = $(".search-box").height();
    $(".contenido-w").height(cw - (60 + head));
    $(".panel-detalles-ticket").height(cw - (60 + head));
    $(".note-pan").height(cw - (60 + head));
    var h = $(".note-pan").height() - pd;
//    $(".content-wrapper").height($(".contenido-w").height());
    $(".content-wrapper").css('min-height', $(".contenido-w").height());
    $(".det-tick").slimScroll({height: '100%'});
    $(".cont-notas").slimScroll({height: (h) + 'px'});
}

function add_note() {
    var data = "id_ticket=" + $("#id_ticket").val() + "&txt-note=" + $("#txt-note").val();
    var nota = $("#txt-note").val();
    $.ajax({
        url: "resources/controller/alta-nota.php",
        type: 'POST',
        data: data,
        dataType: 'json',
        async: false,
        success: function (data, textStatus, jqXHR) {
            hide_loader();
            if (data.correcto === true) {
                show_alert("Guardado", "alert-info");
                upload_file(data.id_nota, nota, data.usuario, data.fecha);
            } else {
                show_alert("Ocurrio un error al guardar", "alert-danger");
            }
        }
    });
    $("#panel-new-note").removeClass("in");
    $("#txt-note").val("");
}

function upload_file(id, nota, user, date) {
    var adjunto = "";
    var data = new FormData(document.getElementById("frm-documento"));
    data.append("id_ticket", $("#id_ticket").val());
    data.append("id_nota", id);
    $.ajax({
        url: "resources/controller/alta-documento.php",
        type: 'POST',
        data: data,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (data, textStatus, jqXHR) {
            hide_loader();
            if (data.correcto === true) {
                var file = '<p class="text-muted">' +
                        '<span class="glyphicon glyphicon-paperclip"></span> <a href="#" title="' + data.fecha + '" onclick="javascript: ver_documento(' + data.id_doc + ')">' + data.nombre_doc + '</a>' +
                        '</p>';
                $(file).prependTo($("#lista_documentos"));
                $("#archivo_nuevo").val('');
                adjunto = '<span class="pull-right glyphicon glyphicon-paperclip" style="padding-right:10px;"></span>';
            }


            var item_nota = '<div class="post">'
                    + '<div class="">'
                    + '<span class="username" style="margin-left: 15px;">'
                    + user
                    + '<span> - </span>'
                    + date
                    + adjunto
                    + '</span>'
                    + '</div>'
                    + '<p style="margin-left: 15px; font-size:14px;">'
                    + nota
                    + '</p>'
                    + '</div>';
            $(item_nota).prependTo($(".contenedor-notas"));
        }
    });
}

function registra_nota_archivo(form) {
    show_loader();
    var formData = new FormData(document.getElementById(form));
    formData.append('id_ticket', $("#id_ticket").val());
    formData.append('stts_ticket', $("#stts_ticket").val());
    var regreso = "error";
    var file = '';

    $.ajax({
        url: "resources/controller/estructura.php",
        type: 'POST',
        data: "id=" + $("#id_ticket").val(),
        dataType: 'json',
        async: false,
        success: function (data, textStatus, jqXHR) {
            var url = '';
            if (data == 1) {
                url = "resources/controller/controller-notas-documentos_n.php";
            } else
            if (data == 0) {
                url = "resources/controller/controller-notas-documentos.php";
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                async: false,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.num_docs) {
                        for (var i = 1; i < data.num_docs; i++) {
                            if (data["doc_correcto" + i] === true) {
                                regreso = "correcto";
                                add_html_doc(data.fecha, data["id_doc" + i], data["nombre_doc" + i]);
                                $("#ultima_actualizacion").html(data.fecha);
                                file = '<span class="pull-right glyphicon glyphicon-paperclip" style="padding-right:10px;"></span>';
                            }
                        }
                    }

                    if (data.correcto === true) {
                        regreso = "correcto";
                        $("#ultima_actualizacion").html(data.fecha);
                        add_html_note(data.usuario, data.fecha, data.nota, file, data.id_nota);
                    }
//           
                    hide_loader();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    show_alert(textStatus, "alert-danger");
                }

            });

        }

    });


    $("#panel-new-note").removeClass("in");
    $("#txt-note").val("");
    return regreso;
}

function add_html_note(user, date, nota, adjunto, id_nota) {
    var controles = '';
    if ($("#usuario_elimina_nota").val() === "1") {
        controles = controles + '<button class="btn-link link-muted pull-right" onclick="javascript:baja_nota(' + id_nota + ')"> <span class="glyphicon glyphicon-trash"></span> </button>';
    }

    if ($("#usuario_edita_nota").val() === "1") {
        controles = controles + '<button class="btn-link link-muted pull-right" onclick="javascript:edita_nota(' + id_nota + ')"><span class = "glyphicon glyphicon-pencil"></span> </button>';
    }


    var item_nota = '<div class="post" id="div_post_' + id_nota + '">'
            + '<div class="">'
            + '<b><span class="username" style="margin-left: 15px;">'
            + user
            + '<span> - </span>'
            + date
            + controles
            + adjunto
            + '</span></b>'
            + '</div>'
            + '<p style="margin-left: 15px; font-size:14px; font-family:HelveticaNeue" class="texto-nota-' + id_nota + '" >'
            + nota
            + '</p>'
            + '</div>';
    $(item_nota).prependTo($(".contenedor-notas"));
}

function add_html_doc(fecha, id_doc, nombre_doc) {
//    var file = '<p class="text-muted">' +
//            '<span class="glyphicon glyphicon-paperclip"></span> <a href="#" title="' + fecha + '" onclick="javascript: ver_documento(' + id_doc + ')">' + nombre_doc + '</a>' +
//            '</p>';
    var controles = '';
    if ($("#usuario_elimina_adjunto").val() === "1") {
        controles = '<button class="btn-link link-muted pull-right" onclick="javascript:baja_archivo(' + id_doc + ')"> <span class="glyphicon glyphicon-trash"></span> </button>';
    }


    var file = '<div class="post" id="div_post_adjunto_' + id_doc + '">'
            + '<div class="">'
            + '<b><span class="username" style="margin-left: 5px;">'
            + $("#nombre_usuario_logueado").val()
            + '<span> - </span>'
            + fecha
            + controles
            + '</span>'
            + '</b>'
            + '</div>'
            + '<p style="margin-left: 5px;font-size:14px;font-family:HelveticaNeue">'
            + '<span class="glyphicon glyphicon-paperclip"></span> <a href="#" onclick="javascript: ver_documento(' + id_doc + ')">' + nombre_doc + '</a>'
            + '</p>'
            + '</div>';

    $(file).prependTo($("#lista_documentos"));
}

function borrar_documento(obj) {
    if (confirm("Confirma eliminar archivo !") == true) {
        show_loader();
        $(obj).parents("li").remove();
        hide_loader();
        show_alert("archivo eliminado", "alert-info");
    }
}

function change_status(op) {
    show_alert("Estatus cambiado", "alert-info");
}

function muestra_estadisticas(estadistica, restriccion) {
    $("#title-est").html(estadistica);
    $("#report-option").val(estadistica);
    $("#report-restriccion").val(restriccion);
    $.ajax({
        url: "resources/tablas/tbl-estadisticas.php",
        type: 'POST',
        data: 'restriccion=' + restriccion,
        success: function (data, textStatus, jqXHR) {

            $("#contenedor-estadisticas").html(data);
            $("#categorias").addClass("active");
            $("#li-categoria").addClass("active");
            $("#ejecutivos").removeClass("active");
            $("#li-ejecutivos").removeClass("active");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
}

function ver_documento(id_doc) {
    window.open('documentos.php?id_doc=' + id_doc, "", 'width=800, height=600');
}

function remove_class_navbar() {
    $(".link-item-navbar").removeClass("active");
}

function collapse_preview() {
    //var date = new Date("Februari 10, 2013");
    var date = new Date();
    date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();

    if ($(".col-preview").hasClass("in")) {
        $("#panel-tickets-list-p").css("height", "auto");
        $(".col-stts").removeClass("no-padding");

        $("#accion_ticket").val("2");
        $(".col-preview").removeClass("in");
        $(".col-tickets").animate({width: '100%'}, 500);
        $(".icon-svp").addClass("fa-toggle-off");
        $(".icon-svp").removeClass("fa-toggle-on");
        $(".btn-show-preview").prop("title", "Mostrar vista previa");
        $(".scroll-list-tick").slimScroll({destroy: true});
        $(".cat-col-det-tick").removeClass("hidden");
        document.cookie = "switch=off" + ";" + expires + ";path=/";
        $(".col-footer-detalles-ticket").css("width", "20%");

        $(".container-detalles-ticket").removeClass("col-lg-12");
        $(".container-detalles-ticket").removeClass("col-md-12");
        $(".container-detalles-ticket").addClass("col-lg-offset-1");
        $(".container-detalles-ticket").addClass("col-md-offset-1");
        $(".container-detalles-ticket").addClass("col-lg-10");
        $(".container-detalles-ticket").addClass("col-md-10");

        //$(".filter-pnl-collapsable").css("width", "100%");
    } else {
        $(".col-stts").addClass("no-padding");

        $("#accion_ticket").val("1");
        $(".col-tickets").animate({width: '55%'}, 500);
        $(".btn-show-preview").prop("title", "Ocultar vista previa");
        $(".icon-svp").addClass("fa-toggle-on");
        $(".icon-svp").removeClass("fa-toggle-off");
        setTimeout(function () {
            $(".col-preview").addClass("in");
        }, 500);
        $(".cat-col-det-tick").addClass("hidden");
        $(".scroll-list-tick").slimScroll({height: "100%"});
        document.cookie = "switch=on" + ";" + expires + ";path=/";
        $(".col-footer-detalles-ticket").css("width", "27%");

        $(".container-detalles-ticket").removeClass("col-lg-10");
        $(".container-detalles-ticket").removeClass("col-md-10");
        $(".container-detalles-ticket").removeClass("col-lg-offset-1");
        $(".container-detalles-ticket").removeClass("col-md-offset-1");
        $(".container-detalles-ticket").addClass("col-lg-12");
        $(".container-detalles-ticket").addClass("col-md-12");

        //$(".filter-pnl-collapsable").css("width", "55%");
    }
}

function exportar_reporte() {
    window.location.href = "reporte_excel.php";
}


function getURL(key) {
    key = key.replace(/[\[]/, '\\[');
    key = key.replace(/[\]]/, '\\]');
    var pattern = "[\\?&]" + key + "=([^&#]*)";
    var regex = new RegExp(pattern);
    var url = unescape(window.location.href);
    var results = regex.exec(url);
    if (results === null) {
        return null;
    } else {
        return results[1];
    }
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

function show_loader() {
    $(".loader").show();
}

function hide_loader() {
    $(".loader").hide();
}