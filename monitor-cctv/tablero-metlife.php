<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}
include 'resources/connection/conexion.php';
include 'utils/constantes.php';

$mysqli->query("SET NAMES 'UTF8'");

$total_activos = 0;

$total_resueltos = 0;

$query = "";


/* * ****************ACTIVOS********************* */

$query_ag = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='1' and unidad_negocio='1102'";
$resultado_ag = $mysqli->query($query_ag);
$fila_ag = $resultado_ag->fetch_assoc();

$query_me = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='1' and unidad_negocio='1103'";
$resultado_me = $mysqli->query($query_me);
$fila_me = $resultado_me->fetch_assoc();

$query_ju = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='1' and unidad_negocio='1104'";
$resultado_ju = $mysqli->query($query_ju);
$fila_ju = $resultado_ju->fetch_assoc();

$total = $fila_ag['total_activos_ag'] + $fila_me['total_activos_me'] + $fila_ju['total_activos_ju'];
$promedio_activos = $total / 3;


/* * ****************Resueltos********************* */
$query_ag_r = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='2' and unidad_negocio='1102'";
$resultado_ag_r = $mysqli->query($query_ag_r);
$fila_ag_r = $resultado_ag_r->fetch_assoc();

$query_me_r = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='2' and unidad_negocio='1103'";
$resultado_me_r = $mysqli->query($query_me_r);
$fila_me_r = $resultado_me_r->fetch_assoc();

$query_ju_r = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='2' and unidad_negocio='1104'";
$resultado_ju_r = $mysqli->query($query_ju_r);
$fila_ju_r = $resultado_ju_r->fetch_assoc();

$total_r = $fila_ag_r['total_activos_ag'] + $fila_me_r['total_activos_me'] + $fila_ju_r['total_activos_ju'];
$promedio_resueltos = $total_r / 3;

/* * ****************cancelados********************* */
$query_ag_c = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='4' and unidad_negocio='1102'";
$resultado_ag_c = $mysqli->query($query_ag_c);
$fila_ag_c = $resultado_ag_c->fetch_assoc();

$query_me_c = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='4' and unidad_negocio='1103'";
$resultado_me_c = $mysqli->query($query_me_c);
$fila_me_c = $resultado_me_c->fetch_assoc();

$query_ju_c = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='4' and unidad_negocio='1104'";
$resultado_ju_c = $mysqli->query($query_ju_c);
$fila_ju_c = $resultado_ju_c->fetch_assoc();

$total_cancelados = $fila_ag_c['total_activos_ag'] + $fila_me_c['total_activos_me'] + $fila_ju_c['total_activos_ju'];
$promedio_cancelados = $total_cancelados / 3;

/* * ****************cerrados********************* */
$query_ag_ce = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='3' and unidad_negocio='1102'";
$resultado_ag_ce = $mysqli->query($query_ag_ce);
$fila_ag_ce = $resultado_ag_ce->fetch_assoc();

$query_me_ce = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='3' and unidad_negocio='1103'";
$resultado_me_ce = $mysqli->query($query_me_ce);
$fila_me_ce = $resultado_me_ce->fetch_assoc();

$query_ju_ce = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='3' and unidad_negocio='1104'";
$resultado_ju_ce = $mysqli->query($query_ju_ce);
$fila_ju_ce = $resultado_ju_ce->fetch_assoc();

$total_cerrados = $fila_ag_ce['total_activos_ag'] + $fila_me_ce['total_activos_me'] + $fila_ju_ce['total_activos_ju'];
$promedio_cerrados = $total_cerrados / 3;


$contador = 0;
$bandera1 = 0;
$bandera2 = 0;
$bandera3 = 0;



while ($contador < count($_SESSION['usr_hospitales'])) {


    if ($_SESSION['usr_hospitales'][$contador] == '1102') {
        $bandera1 = '1';
    } else
    if ($_SESSION['usr_hospitales'][$contador] == '1103') {
        $bandera2 = '1';
    } else
    if ($_SESSION['usr_hospitales'][$contador] == '1104') {
        $bandera3 = '1';
    }

    $contador++;
}

//echo $bandera1 . " " . $bandera2 . " " . $bandera3;

function get_format($df) {

    $str = '';
    $str .= ($df->invert == 1) ? ' - ' : '';
    if ($df->y > 0) {
        // years
        $str .= ($df->y > 1) ? $df->y . ' A ' : $df->y . ' A ';
    } if ($df->m > 0) {
        // month
        $str .= ($df->m > 1) ? $df->m . ' M ' : $df->m . ' M ';
    } if ($df->d > 0) {
        // days

        $str .= ($df->d > 1) ? $df->d . ' - ' : $df->d . ' - ';
    } if ($df->h > 0) {
        // hours
        $str .= ($df->h > 1) ? $df->h . 'Hrs' : $df->h . 'Hrs';
    } if ($df->i > 0) {
        // minutes
        $str .= ($df->i > 1) ? $df->i . 'M' : $df->i . 'M';
    } if ($df->s > 0) {
        // seconds
        $str .= ($df->s > 1) ? $df->s . '' : $df->s . '';
    }


    echo $str;
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;
              charset = utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>

<?php require 'resources/components/includes.php'; ?>
        <script src="dist/js/html2canvas.js"></script>
        <script src="plugins/jspdf/jspdf.js"></script>

        <script src="plugins/jspdf/filesaver.js"></script>
        <script src="plugins/jspdf/libs/sprintf.js"></script>
        <script src="plugins/jspdf/libs/base64.js"></script>


        <style>
            .dashboard-control{
                width: 20%;
                float: left;
                padding-left: 15px;
                padding-right: 15px;
            }

            @media(max-width:768px){
                .dashboard-control{
                    width: 100%;
                    float: left;
                    padding-left: 15px;
                    padding-right: 15px;
                }
            }
        </style>
        <script>
            var indice_pag = 0;
            $(document).ready(function (e) {
                remove_class_navbar();
                $(".link-dashboard").addClass("active");

                $("#lnkAntUltTkts").click(function (e) {
                    e.preventDefault();
//alert("Test");
                    if (indice_pag > 0) {
                        indice_pag--;
                        $("#tbdUltimosTkts").load('utils/funciones-dashboard.php', {opcion: 'ajx_mostrar_tickets_activos_pag', indice_pag: indice_pag});
                    }

                });

                $("#lnkSigUltTkts").click(function (e) {
                    e.preventDefault();
//alert("Test");
                    var tieneMas = $("#hdnTieneMas").val() == 1;
                    if (tieneMas) {
                        indice_pag++;
                        $("#tbdUltimosTkts").load('utils/funciones-dashboard.php', {opcion: 'ajx_mostrar_tickets_activos_pag', indice_pag: indice_pag});
                    }

                });

                if (<?php echo $_SESSION['rol'] ?> == '1')
                {
                    $("#b_panel").show();
                    $("#t_b").html('&nbsp;Panel de Configuracion');
                    $("#configuracion").click(function (e) {

                        if ($("#panel_conf").css('display') == 'none') {
                            $("#panel_conf").css('display', 'block');
                            $("#t_b").html('&nbsp; Cerrar Configuracion');
                            $("#tbl_distribucion_e").hide();
                            $("#tbl_distribucion_t").show();
                            $("#tbl_distribucion_h").hide();
                        } else {
                            $("#panel_conf").css('display', 'none');
                            $("#t_b").html('&nbsp; Panel de Configuracion');
                        }
                    });

                }else
                   $("#b_panel").hide(); 



                var bandera1 = '<?php echo $bandera1; ?>';
                var bandera2 = '<?php echo $bandera2; ?>';
                var bandera3 = '<?php echo $bandera3; ?>';

                console.log(bandera1 + bandera2 + bandera3);



                if ((bandera1 == "1") && (bandera2 == '1') && (bandera3 == '1')) {
                    todos();

                }

                if (bandera1 == "1") {
                    aguscalientes();
                }

                if (bandera2 == "1") {
                    merida();
                }

                if (bandera3 == "1") {
                    juarez();
                }



            });


            window.onload = function () {


                new Chart(document.getElementById("myChart"), {

                    type: 'bar',
                    data: {
                        labels: ["Activos", "Resueltos", "Cancelados", "Cerrados"],
                        datasets: [
                            {
                                type: 'line',
                                label: "Promedio",
                                backgroundColor: "#FFEE58",
                                borderColor: "#FFF176",
                                data: [<?php echo $promedio_activos ?>, <?php echo $promedio_resueltos ?>, <?php echo $promedio_cancelados ?>, <?php echo $promedio_cerrados ?>],
                                fill: false
                            },
                            {
                                label: "Aguscalientes",
                                backgroundColor: "#90CAF9",
                                data: [<?php echo $fila_ag['total_activos_ag'] ?>, <?php echo $fila_ag_r['total_activos_ag'] ?>, <?php echo $fila_ag_c['total_activos_ag'] ?>, <?php echo $fila_ag_ce['total_activos_ag'] ?>]
                            }, {
                                label: "Merida",
                                backgroundColor: "#424242",
                                data: [<?php echo $fila_me['total_activos_me'] ?>, <?php echo $fila_me_r['total_activos_me'] ?>, <?php echo $fila_me_c['total_activos_me'] ?>, <?php echo $fila_me_ce['total_activos_me'] ?>]
                            }, {
                                label: "Juarez",
                                backgroundColor: "#A5D6A7",
                                data: [<?php echo $fila_ju['total_activos_ju'] ?>, <?php echo $fila_ju_r['total_activos_ju'] ?>, <?php echo $fila_ju_c['total_activos_ju'] ?>, <?php echo $fila_ju_ce['total_activos_ju'] ?>]
                            }
                        ]
                    },
                    options: {
                        responsive: true, //True por defecto
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true //Si es false empezaría en el valor de la barra más pequeña
                                    }
                                }]
                        }
                    }

                });
            }



            function getScreen() {
                var caption = $('#caption-input').val();
                $("#caption-text").html(caption);
                $("#panel").hide();
                var element = jQuery("#imprimir")[0];
                show_loader();
                html2canvas(element, {
                    dpi: 192,
                    scale: 2,
                    background: '#FFFFFF',
                    onrendered: function (canvas) {
                        $("#blank").attr('href', canvas.toDataURL("image/png"));
                        //  var imgData = canvas.toDataURL('image/png'); 
                        //  var doc = new jsPDF('p','pt', 'a4', true);
                        //  doc.addImage(imgData, 'PNG', 10, 10);
                        //  doc.save('mipdf.pdf');



                        $("#blank").attr('download', 'reporte-metlife' + '.png');
                        $("#blank")[0].click();
                        hide_loader();
                    }
                });
            }


            function aguscalientes() {
                $("#a_vencidos").show();
                $("#m_vencidos").hide();
                $("#j_vencidos").hide();

                $("#a_vencidos").show();
                $("#m_pvencidos").hide();
                $("#j_pvencidos").hide();

                $("#a_vencidos").show();
                $("#m_tiempo").hide();
                $("#j_tiempo").hide();

                $("#aguascalientes").show();
                $("#merida").hide();
                $("#juarez").hide();

                $("#todos").attr('class', 'btn btn-primary');
                $("#b_juarez").attr('class', 'btn btn-primary');
                $("#b_merida").attr('class', 'btn btn-primary');
                $("#b_aguascalientes").attr('class', 'btn btn-success');

                $("#advertencia").hide();
                $("#advertencia_min").show();

                $("#warning").hide();
                $("#warning_min").show();

                $("#exito").hide();
                $("#exito_min").show();

                $("#totales_aguascalientes").show();
                $("#totales_merida").hide();
                $("#totales_juarez").hide();
                $("#totales_t").hide();

                $('#myChart').remove();
                $('.chart').append('<canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"><canvas>');


                new Chart(document.getElementById("myChart"), {

                    type: 'bar',
                    data: {
                        labels: ["Activos", "Resueltos", "Cancelados", "Cerrados"],
                        datasets: [

                            {
                                label: "Aguscalientes",
                                backgroundColor: "#90CAF9",
                                data: [<?php echo $fila_ag['total_activos_ag'] ?>, <?php echo $fila_ag_r['total_activos_ag'] ?>, <?php echo $fila_ag_c['total_activos_ag'] ?>, <?php echo $fila_ag_ce['total_activos_ag'] ?>]
                            }
                        ]
                    },
                    options: {
                        responsive: true, //True por defecto
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true //Si es false empezaría en el valor de la barra más pequeña
                                    }
                                }]
                        }
                    }

                });




            }

            function juarez() {
                $("#j_vencidos").show();
                $("#m_vencidos").hide();
                $("#a_vencidos").hide();

                $("#j_pvencidos").show();
                $("#m_pvencidos").hide();
                $("#a_pvencidos").hide();

                $("#j_tiempo").show();
                $("#m_tiempo").hide();
                $("#a_tiempo").hide();

                $("#juarez").show();
                $("#merida").hide();
                $("#aguascalientes").hide();

                $("#todos").attr('class', 'btn btn-primary');
                $("#b_juarez").attr('class', 'btn btn-success');
                $("#b_merida").attr('class', 'btn btn-primary');
                $("#b_aguascalientes").attr('class', 'btn btn-primary');

                $("#advertencia").hide();
                $("#advertencia_min").show();

                $("#warning").hide();
                $("#warning_min").show();

                $("#exito").hide();
                $("#exito_min").show();

                $("#totales_aguascalientes").hide();
                $("#totales_merida").hide();
                $("#totales_juarez").show();
                $("#totales_t").hide();

                $('#myChart').remove();
                $('.chart').append('<canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"><canvas>');


                new Chart(document.getElementById("myChart"), {

                    type: 'bar',
                    data: {
                        labels: ["Activos", "Resueltos", "Cancelados", "Cerrados"],
                        datasets: [

                            {
                                label: "Juarez",
                                backgroundColor: "#A5D6A7",
                                data: [<?php echo $fila_ju['total_activos_ju'] ?>, <?php echo $fila_ju_r['total_activos_ju'] ?>, <?php echo $fila_ju_c['total_activos_ju'] ?>, <?php echo $fila_ju_ce['total_activos_ju'] ?>]
                            }
                        ]
                    },
                    options: {
                        responsive: true, //True por defecto
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true //Si es false empezaría en el valor de la barra más pequeña
                                    }
                                }]
                        }
                    }

                });
            }

            function merida() {
                $("#j_vencidos").hide();
                $("#m_vencidos").show();
                $("#a_vencidos").hide();

                $("#j_pvencidos").hide();
                $("#m_pvencidos").show();
                $("#a_pvencidos").hide();

                $("#j_tiempo").hide();
                $("#m_tiempo").show();
                $("#a_tiempo").hide();

                $("#juarez").hide();
                $("#merida").show();
                $("#aguascalientes").hide();

                $("#advertencia").hide();
                $("#advertencia_min").show();

                $("#warning").hide();
                $("#warning_min").show();

                $("#exito").hide();
                $("#exito_min").show();

                $("#todos").attr('class', 'btn btn-primary');
                $("#b_juarez").attr('class', 'btn btn-primary');
                $("#b_merida").attr('class', 'btn btn-success');
                $("#b_aguascalientes").attr('class', 'btn btn-primary');

                $("#totales_aguascalientes").hide();
                $("#totales_merida").show();
                $("#totales_juarez").hide();
                $("#totales_t").hide();

                $('#myChart').remove();
                $('.chart').append('<canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"><canvas>');

                new Chart(document.getElementById("myChart"), {

                    type: 'bar',
                    data: {
                        labels: ["Activos", "Resueltos", "Cancelados", "Cerrados"],
                        datasets: [

                            {
                                label: "Merida",
                                backgroundColor: "#424242",
                                data: [<?php echo $fila_me['total_activos_me'] ?>, <?php echo $fila_me_r['total_activos_me'] ?>, <?php echo $fila_me_c['total_activos_me'] ?>, <?php echo $fila_me_ce['total_activos_me'] ?>]
                            }
                        ]
                    },
                    options: {
                        responsive: true, //True por defecto
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true //Si es false empezaría en el valor de la barra más pequeña
                                    }
                                }]
                        }
                    }

                });
            }

            function todos() {
                $("#j_vencidos").show();
                $("#m_vencidos").show();
                $("#a_vencidos").show();

                $("#j_pvencidos").show();
                $("#m_pvencidos").show();
                $("#a_pvencidos").show();

                $("#j_tiempo").show();
                $("#m_tiempo").show();
                $("#a_tiempo").show();

                $("#juarez").show();
                $("#merida").show();
                $("#aguascalientes").show();


                $("#todos").attr('class', 'btn btn-success');
                $("#b_juarez").attr('class', 'btn btn-primary');
                $("#b_merida").attr('class', 'btn btn-primary');
                $("#b_aguascalientes").attr('class', 'btn btn-primary');

                $("#advertencia").show();
                $("#advertencia_min").hide();

                $("#warning").show();
                $("#warning_min").hide();

                $("#exito").show();
                $("#exito_min").hide();

                $("#totales_aguascalientes").show();
                $("#totales_merida").show();
                $("#totales_juarez").show();
                $("#totales_t").show();


                $('#myChart').remove();
                $('.chart').append('<canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"><canvas>');

                new Chart(document.getElementById("myChart"), {

                    type: 'bar',
                    data: {
                        labels: ["Activos", "Resueltos", "Cancelados", "Cerrados"],
                        datasets: [
                            {
                                type: 'line',
                                label: "Promedio",
                                backgroundColor: "#FFEE58",
                                borderColor: "#FFF176",
                                data: [<?php echo $promedio_activos ?>, <?php echo $promedio_resueltos ?>, <?php echo $promedio_cancelados ?>, <?php echo $promedio_cerrados ?>],
                                fill: false
                            },
                            {
                                label: "Aguscalientes",
                                backgroundColor: "#90CAF9",
                                data: [<?php echo $fila_ag['total_activos_ag'] ?>, <?php echo $fila_ag_r['total_activos_ag'] ?>, <?php echo $fila_ag_c['total_activos_ag'] ?>, <?php echo $fila_ag_ce['total_activos_ag'] ?>]
                            }, {
                                label: "Merida",
                                backgroundColor: "#424242",
                                data: [<?php echo $fila_me['total_activos_me'] ?>, <?php echo $fila_me_r['total_activos_me'] ?>, <?php echo $fila_me_c['total_activos_me'] ?>, <?php echo $fila_me_ce['total_activos_me'] ?>]
                            }, {
                                label: "Juarez",
                                backgroundColor: "#A5D6A7",
                                data: [<?php echo $fila_ju['total_activos_ju'] ?>, <?php echo $fila_ju_r['total_activos_ju'] ?>, <?php echo $fila_ju_c['total_activos_ju'] ?>, <?php echo $fila_ju_ce['total_activos_ju'] ?>]
                            }
                        ]
                    },
                    options: {
                        responsive: true, //True por defecto
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true //Si es false empezaría en el valor de la barra más pequeña
                                    }
                                }]
                        }
                    }

                });


            }
        </script>



    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>
        <?php include 'utils/funciones.php'; ?>
<?php include './resources/tablas/tbl-metlife.php'; ?>

        <input type="hidden" id="report-option" value="">
        <input type="hidden" id="report-restriccion" value="">

        <div class="content-wrapper" style="padding-bottom: 35px;">

            <div class="pull-right" id="b_panel" style="z-index: 1; position: relative; padding-right: 50px; padding-bottom: 20px" >
                <a href="#" class="btn btn-sm btn-default" id="configuracion"><span class="glyphicon glyphicon-cog" ><span id="t_b" style="font-family: HelveticaNeueRoman;"></span></span></a>
            </div>

            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="margin-bottom: 70px;">
                <!-- Content Header (Page header) -->


                <div style="width:100%; height: available; display: none; background-color: #FAFAFA;" id='panel_conf'>

<?php include './resources/controller/configuracion_rmetlife.php'; ?>

                </div>







                <!-- Main content -->
                <section class="content" id="imprimir">
                    <section class="content-header no-pad-top" style="text-align: center; padding-bottom: 20px; padding-top: 15px; position: relative" >

                        <h3 class="no-margin">
                            Sistema de Gestion de Tickets MetLife <br><br>
                            <?php
                            $fechaactual = getdate();
                            date_default_timezone_set('America/Mexico_City');
                            echo $fecha = strftime("%d/%m/%Y %H:%M:%S", time());
                            ?>
                        </h3>


                    </section>



                    <!---   <div class="row">
                           <div class="col-md-12">
                               <div class="box box-primary">
                                   <div class="box-header with-border" style="text-align: center">
                                       <h3 class="box-title"></h3>
                                   </div>
                                   <div class="box-body">
                                       <div class="chart">
                                           <canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"></canvas>
                                       </div>
                                   </div>
   
   
   
                                   <br>
                               </div>
                           </div>
                       </div>--->



                    <table class="table-s table table-hover table-responsive tbl-det-tickets" style="width: 100%; " >
                        <thead class="box box-primary">
                            <tr style="background-color: #64b5f6; color: white; border-bottom: 2px solid white; border-top:2px solid white;  ">
                                <td style="width: 10%; text-align: center;" class="tot">Hospital</td>
                                <td style="width: 10%; text-align: center;" class="tot">Activos</td>
                                <td style="width: 10%; text-align: center;" class="tot">Resueltos</td>
                                <td style="width: 10%; text-align: center;" class="tot">Cancelados</td>
                                <td style="width: 10%; text-align: center;" class="tot">Cerrados</td>

                            </tr>
                        </thead>

                        <tbody style="border-bottom: hidden" >
                            <tr id="totales_aguascalientes">
                                <td class="tot" style=" text-align: center;">AGUASCALIENTES</td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ag['total_activos_ag'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ag_r['total_activos_ag'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ag_c['total_activos_ag'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ag_ce['total_activos_ag'] ?></td>
                            </tr>
                            <tr id="totales_merida">
                                <td class="tot" style=" text-align: center;">MERIDA</td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_me['total_activos_me'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_me_r['total_activos_me'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_me_c['total_activos_me'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_me_ce['total_activos_me'] ?></td>
                            </tr>
                            <tr id="totales_juarez">
                                <td class="tot" style=" text-align: center;">JUAREZ</td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ju['total_activos_ju'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ju_r['total_activos_ju'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ju_c['total_activos_ju'] ?></td>
                                <td class="tot" style=" text-align: center;"><?php echo $fila_ju_ce['total_activos_ju'] ?></td>
                            </tr>

                            <tr id="totales_t">
                                <td class="tot" style=" text-align: center; background-color: #eeeeee">TOTAL</td>
                                <td class="tot" style=" text-align: center; background-color: #eeeeee"><?php echo $totales_act = $fila_ag['total_activos_ag'] + $fila_me['total_activos_me'] + $fila_ju['total_activos_ju']; ?></td>
                                <td class="tot" style=" text-align: center; background-color: #eeeeee"><?php echo $totales_res = $fila_ag_r['total_activos_ag'] + $fila_me_r['total_activos_me'] + $fila_ju_r['total_activos_ju']; ?></td>
                                <td class="tot" style=" text-align: center; background-color: #eeeeee"><?php echo $totales_can = $fila_ag_c['total_activos_ag'] + $fila_me_c['total_activos_me'] + $fila_ju_c['total_activos_ju']; ?></td>
                                <td class="tot" style=" text-align: center; background-color: #eeeeee"><?php echo $totales_cer = $fila_ag_ce['total_activos_ag'] + $fila_me_ce['total_activos_me'] + $fila_ju_ce['total_activos_ju']; ?></td>
                            </tr>
                        </tbody>
                    </table>



                    <!--hopitales-->

                    <!---   <div class="row" >
   
   
                           <div class="col-lg-3 col-xs-6" style="width: 33.2%">
                               <a id="atrasados" href="#">
                                   <div class="small-box bg-red">
                                       <div class="inner">
                                           <h3 style="font-size:24px; border-bottom: 1px solid white">Tickets Atrasados</h3>
                                           <p id="a_vencidos"></p>
                                           <p id="m_vencidos"></p>
                                           <p id="j_vencidos"></p>
                                       </div>
                                       <div class="icon">
                                           <i id="warning_min" hidden="true"><img src="dist/img/warning_min.png" style="padding-top: 35%;"></i>
                                           <i id="warning"><img src="dist/img/warning.png"  style="padding-top: 80%;"></i>
                                       </div>
                                   </div>
                               </a>
                           </div>
   
                           <div class="col-lg-3 col-xs-6" style="width: 33.2%">
                               <a id="atiempo" href="#">
                                   <div class="small-box bg-yellow">
                                       <div class="inner">
                                           <h3 style="font-size:24px; border-bottom: 1px solid white">Tickets por Vencer</h3>
                                           <p id="a_pvencidos"></p>
                                           <p id="m_pvencidos"></p>
                                           <p id="j_pvencidos"></p>
                                       </div>
                                       <div class="icon">
                                           <i id="advertencia_min" hidden="true"><img src="dist/img/advertencia_min.png" style="padding-top: 35%;"></i>
                                           <i id="advertencia"><img src="dist/img/advertencia.png" style="padding-top: 80%;"></i>
                                       </div>
                                   </div>
                               </a>
                           </div>
   
   
                           <div class="col-lg-3 col-xs-6" style="width: 33.2%">
                               <a href="#">
                                   <div class="small-box bg-green">
                                       <div class="inner">
                                           <h3 style="font-size:24px; border-bottom: 1px solid white">Tickets en Tiempo</h3>
                                           <p id="a_tiempo"></p>
                                           <p id="m_tiempo"></p>
                                           <p id="j_tiempo"></p>
                                       </div>
                                       <div class="icon">
                                           <i id="exito_min" hidden="true"><img src="dist/img/exito_min.png" style="padding-top: 35%;"></i>
                                           <i id="exito"><img src="dist/img/exito.png" style="padding-top: 80%;"></i>
                                       </div>
                                   </div>
                               </a>
                           </div>
                       </div>----->
                    <div id="aguascalientes">
                        <h4><strong>AGUASCALIENTES</strong></h4>
                        <table class="table-s table table-hover table-responsive tbl-det-tickets">
                            <thead class="box box-primary">
                                <tr>
                                    <td style="width: 3%; text-align: center;" class="tot">Folio</td>
                                    <td style="width: 5%; text-align: center;" class="tot">Estatus</td>
                                    <td style="width: 20%; text-align: center;" class="tot">Categoria</td>
                                    <td style="width: 40%; text-align: center;" class="tot">Asignado a</td>
                                    <td style="width: 15%; text-align: center;" class="tot">Creación Ticket</td>
                                    <td style="width: 13%; text-align: center;" class="tot">Horas</td>
                                </tr>
                            </thead>

                            <tbody style="border-bottom: hidden">
                                <?php
                                atrasados('1102');
                                ?>
                            </tbody>
                        </table>


                        <table class="table-s table table-hover table-responsive tbl-det-tickets" style="margin-top:-20px">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody style="border-bottom: hidden">
<?php vencer('1102') ?>
                            </tbody>
                        </table>



                        <table class="table-s table table-hover table-responsive tbl-det-tickets"  style="margin-top:-20px">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                atiempo('1102')
                                ?>
                            </tbody>
                        </table>
                    </div>



                    <!--hopitales-->
                    <div id="merida">
                        <h4><strong>MERIDA</strong></h4>
                        <table class="table-s table table-hover table-responsive tbl-det-tickets">
                            <thead class="box box-primary">
                                <tr>
                                    <td style="width: 3%; text-align: center;" class="tot">Folio</td>
                                    <td style="width: 5%; text-align: center;" class="tot">Estatus</td>
                                    <td style="width: 20%; text-align: center;" class="tot">Categoria</td>
                                    <td style="width: 40%; text-align: center;" class="tot">Asignado a</td>
                                    <td style="width: 15%; text-align: center;" class="tot">Creación Ticket</td>
                                    <td style="width: 13%; text-align: center;" class="tot">Horas</td>
                                </tr>
                            </thead>

                            <tbody style="border-bottom: hidden">
                                <?php
                                atrasados('1103');
                                ?>
                            </tbody>
                        </table>


                        <table class="table-s table table-hover table-responsive tbl-det-tickets" style="margin-top:-20px; ">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody style="border-bottom: hidden">
<?php vencer('1103') ?>
                            </tbody>
                        </table>


                        <table class="table-s table table-hover table-responsive tbl-det-tickets"  style="margin-top:-20px">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                atiempo('1103')
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!--hopitales-->

                    <div id="juarez">
                        <h4><strong>JUAREZ</strong></h4>
                        <table class="table-s table table-hover table-responsive tbl-det-tickets">
                            <thead class="box box-primary">
                                <tr>
                                    <td style="width: 3%; text-align: center;" class="tot">Folio</td>
                                    <td style="width: 5%; text-align: center;" class="tot">Estatus</td>
                                    <td style="width: 20%; text-align: center;" class="tot">Categoria</td>
                                    <td style="width: 40%; text-align: center;" class="tot">Asignado a</td>
                                    <td style="width: 15%; text-align: center;" class="tot">Creación Ticket</td>
                                    <td style="width: 13%; text-align: center;" class="tot">Horas</td>
                                </tr>
                            </thead>

                            <tbody style="border-bottom: hidden">
                                <?php
                                atrasados('1104');
                                ?>
                            </tbody>
                        </table>


                        <table class="table-s table table-hover table-responsive tbl-det-tickets" style="margin-top:-20px; ">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody style="border-bottom: hidden">
<?php vencer('1104') ?>
                            </tbody>
                        </table>


                        <table class="table-s table table-hover table-responsive tbl-det-tickets" style="margin-top:-20px; ">
                            <thead style="background-color: white">
                                <tr style="background-color: white">
                                    <td style="width: 3%; text-align: center;"></td>
                                    <td style="width: 5%; text-align: center;" ></td>
                                    <td style="width: 20%; text-align: center;" ></td>
                                    <td style="width: 40%; text-align: center;" ></td>
                                    <td style="width: 15%; text-align: center;" ></td>
                                    <td style="width: 13%; text-align: center;" ></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                atiempo('1104')
                                ?>
                            </tbody>
                        </table>
                    </div>

                </section>
                <div class="pull-right" style="padding-bottom: 40px; padding-right: 15px">
                    <button class="btn-primary" onclick="getScreen();">Desacargar Reporte</button>
                    <a href="" id="blank"></a>
                </div>



            </div>
        </div>
<?php include 'resources/components/footer.php'; ?>
    </body>
</html>