<?php
define("NOMBRE_SISTEMA"     , "Sistema Central de Monitoreo");
define("VERSION"            , "1.3.30");
define("URL_SISTEMA"        , "http://localhost/service/");
define("BTN_T_G_REGRESAR"   , '<button class="btn btn-warning btn-sm btn-funcion" onclick="abrirModalFPRegresar(); save();">Rechazar</button>&nbsp;');
//define("BTN_T_G_ATENDER"    , '<button class="btn btn-success btn-sm btn-funcion" onclick="abrirModalFPResolver(); save();">Atender</button>&nbsp;');
define("BTN_T_G_ATENDER"    , '<button class="btn btn-success btn-sm btn-funcion" id="btn-fp-resolver-ticket">Atender</button>&nbsp;');
define("BTN_T_A_RECHAZAR"   , '<button class="btn btn-danger btn-sm btn-funcion"; onclick="abrirModalFPRechazar(); save();">Rechazar</button>&nbsp;');
define("BTN_T_A_RECHAZAR_C"   , '<button class="btn btn-danger btn-sm btn-funcion"; onclick="abrirModalFPRechazarYCierre(); save();">Rechazar</button>&nbsp;');
//define("BTN_T_A_AUTORIZAR"  , '<button class="btn btn-success btn-sm btn-funcion" onclick="abrirModalFPAutorizar(); save();">Autorizar</button>&nbsp;');
define("BTN_T_A_AUTORIZAR"  , '<button class="btn btn-success btn-sm btn-funcion" id="btn-fp-autorizar-ticket">Autorizar</button>&nbsp;');
define("BTN_T_A_AUTORIZAR_R", '<button class="btn btn-success btn-sm btn-funcion" onclick="abre_modal_resolver_ticket(); save();">Autorizar</button>&nbsp;');
define("BTN_T_ASIGNAR"      , '');
define("BTN_T_RESOLVER"     , '<button class="btn btn-default btn-sm btn-funcion" id="btn-resolver-ts" onclick="abre_modal_resolver_ticket(); save();">Resolver</button>&nbsp');
define("BTN_T_REABRIR"      , '<button class="btn btn-default btn-sm btn-funcion" id="btn-reabrir-t" data-toggle = "modal" onclick="abre_modal_reabrir_ticket(); save();">Reabrir</button>&nbsp');
define("BTN_T_CERRAR"       , '<button class="btn btn-default btn-sm btn-funcion" id="btn-cerrar-t" data-toggle = "modal" onclick="abre_modal_cerrar_ticket(); save();">Cerrar</button>&nbsp');
define("BTN_T_CANCELAR"     , '<button class="btn btn-default btn-sm btn-funcion" id="btn-cancelar-ts" data-toggle = "modal" onclick="abre_modal_cancelar_ticket(); save();">Cancelar</button>&nbsp');
define("BTN_T_AGREGAR_NOTA" , '<button class="btn btn-default btn-sm btn-funcion" data-toggle="modal" onclick="abre_modal_nota(); save();">Agregar nota</button>&nbsp');
define("BTN_N_CATEGORIA"    , '<button class="btn btn-default btn-sm btn-funcion" data-toggle="modal" onclick="recategorizar();">Clasificar</button>&nbsp');
