<?php

/* 1.-asignada       2.-resuelta         3.-cerrada      4.-rechazada  */

function mostrar_botones($usuarioActual, $estatus, $id_ticket, $tipoFlujoProceso, $cat2, $cat3, $cat4, $secFlujo, $unidadNegocio, $usuarioSolicitante) {

    require 'resources/connection/conexion.php';
    require_once 'resources/components/includes.php';
    $mysqli->query("SET NAMES 'UTF8'");


    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">';

    $array_funciones = array();

    if (isset($_SESSION['funciones'])) {
        $array_funciones = $_SESSION['funciones'];

        if ($tipoFlujoProceso != "") {
            //TODO validar si existe un paso posterior, de lo contrario mostrar boton resolver
            $etapaAnt = 0;
            $etapaPos = 0;

            if ($estatus == '1') {
                $query = "select etapa_ant, etapa_pos 
                    from flujo_proceso fp 
                    where fp.ind_activo = true 
                        and fp.id_categoria_2 = '$cat2' 
                        and fp.num_etapa = '$secFlujo' 
                        and unidad_negocio = '$unidadNegocio' ";
                if ($cat3 > 0) {
                    $query .= " and fp.id_categoria_3 = '$cat3' ";
                }

                if ($cat4 > 0) {
                    $query .= " and fp.id_categoria_4 = '$cat4' ";
                }

                //echo $query;

                $resultSet = $mysqli->query($query);
                $rsFPEtapas = $resultSet->fetch_assoc();
                $etapaAnt = $rsFPEtapas['etapa_ant'];
                $etapaPos = $rsFPEtapas['etapa_pos'];

                echo '<input type="hidden" name="hdnEtapaAntFP" id="hdnEtapaAntFP" value="' . $etapaAnt . '">';
                echo '<input type="hidden" name="hdnEtapaPosFP" id="hdnEtapaPosFP" value="' . $etapaPos . '">';
            }

            if (isset($_SESSION['usr_funciones']) && in_array("ver_asignar_ticket_fp", $_SESSION['usr_funciones']) && $estatus == '1') {
                echo BTN_T_ASIGNAR;
            }

            if ($usuarioActual == $_SESSION['id_usuario']) {
                if ($tipoFlujoProceso == "GESTION") {
                    if ($etapaAnt > 0) {
                        echo BTN_T_G_REGRESAR;
                    }
                    if ($etapaPos > 0) {
                        if ($etapaPos == 100) {
                            //TODO Mostrar bototn de resolver ticket
                            echo BTN_T_RESOLVER;
                        } else {
                            echo BTN_T_G_ATENDER;
                        }
                    }
                } else if ($tipoFlujoProceso == "AUTORIZACION") {
                    if ($etapaAnt > 0) {
                        if ($etapaAnt == 999) {
                            echo BTN_T_A_RECHAZAR_C;
                        } else {
                            echo BTN_T_A_RECHAZAR;
                        }
                    }
                    if ($etapaPos > 0) {
                        if ($etapaPos == 100) {
                            //TODO Mostrar bototn de resolver ticket
                            echo BTN_T_A_AUTORIZAR_R;
                        } else {
                            echo BTN_T_A_AUTORIZAR;
                        }
                    }
                }
            }
        } else {
            if (in_array("ver_asignar_ticket", $array_funciones) && ($estatus == '1' /* || $estatus == '2' */) && ($cat2 != '9')) {
                echo BTN_T_ASIGNAR;
            }
            if ($cat2 == '9') {
                echo BTN_N_CATEGORIA;
            }
            if (in_array("ver_resolver_ticket", $array_funciones) && $estatus == '1' && $usuarioActual == $_SESSION['id_usuario']) {
                echo BTN_T_RESOLVER;
            }
        }
        /*echo $array_funciones;
        echo " ";
        echo $estatus;*/
        if (in_array("ver_reabrir_ticket", $array_funciones) && $usuarioSolicitante == $_SESSION['id_usuario'] && $estatus == '2') {
            echo BTN_T_REABRIR;
        } else
        if ($_SESSION['rol'] == '1' && $estatus == '2') {
            echo BTN_T_REABRIR;            
        }
        //echo "-".BTN_T_ASIGNAR;
        //echo "-".BTN_T_REABRIR;

        if (in_array("ver_cerrar_ticket", $array_funciones) && $usuarioSolicitante == $_SESSION['id_usuario'] && $estatus == '2') {
            echo BTN_T_CERRAR;
        } else
        if ($_SESSION['rol'] == '1' && $estatus == '2') {
            echo BTN_T_CERRAR;
        }

        if ((in_array("ver_cancelar_ticket", $array_funciones) && $usuarioActual === "" && $usuarioSolicitante == $_SESSION['id_usuario'] && $estatus == '1') || (in_array("ver_cancelar_ticket_admin", $array_funciones))) {
            echo BTN_T_CANCELAR;
        }

        if ($_SESSION['rol'] == '1' && $estatus == '1') {
            echo BTN_T_CERRAR;
        }
        
       

        if (in_array("ver_agregar_nota", $array_funciones) && ($estatus == '1' || $estatus == '2')) {
            echo BTN_T_AGREGAR_NOTA;
        }
    }

    echo '<button class="btn btn-default btn-sm btn-volver" onclick="show_loader(); javascript:window.location.href=\'' . $_SESSION['pag_ant'] . '\'">Volver</button>&nbsp;';

    if (isset($_SESSION['lista_tickets'])) {
        $tam_lista = count($_SESSION['lista_tickets']);
        for ($i = 0; $i < $tam_lista; $i++) {
            if ($_SESSION['lista_tickets'][$i] == $id_ticket) {
                if (($i - 1) >= 0) {
                    echo '<button style="font-size: 20px; padding: 0px 10px;" class="btn btn-default" title="anterior" onclick="ant_sig(' . $_SESSION['lista_tickets'][$i - 1] . ')"><span aria-hidden="true">&laquo;</span></button>';
                }
                echo "&nbsp;";
                if (($i + 1) < $tam_lista) {
                    echo '<button style="font-size: 20px; padding: 0px 10px;" class="btn btn-default" title="siguiente" onclick="ant_sig(' . $_SESSION['lista_tickets'][$i + 1] . ')"><span aria-hidden="true">&raquo;</span></button>';
                }
            }
        }
    }
    echo '</div>';
}

?>