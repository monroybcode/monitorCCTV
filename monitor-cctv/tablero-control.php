<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}
include 'resources/connection/conexion.php';
include 'utils/constantes.php';

$mysqli->query("SET NAMES 'UTF8'");

$total_asignadas_a_mi = 0;
$total_activos = 0;
$total_pendientes_asignar = 0;
$total_resueltos = 0;
$total_cerrados = 0;

$query = "";
$id = $_SESSION['id_usuario'];
//--Asignados a mi
$query = "select count(t.id_ticket) as total_asignadas_a_mi
from tickets t 
JOIN usuario_hospital uh on t.unidad_negocio=uh.hospital and uh.usuario='" . $_SESSION['id_usuario'] . "' 
where true and t.estatus!=3 and t.usuario_actual='" . $_SESSION['id_usuario'] . "' or t.usuario_resuelve='" . $_SESSION['id_usuario'] . "'";
//echo $query;
$resultado = $mysqli->query($query);
$fila = $resultado->fetch_assoc();
$total_asignadas_a_mi = $fila['total_asignadas_a_mi'];

/*
  if ($_SESSION['nombre_rol'] == 'Administrador' || $_SESSION['nombre_rol'] == 'Alta Dirección') {
  $query = "select count(t.id_ticket) as total_activos
  from tickets t
  where estatus = 1";
  } else {
  $query = "select count(t.id_ticket) as total_activos
  from tickets t
  JOIN usuario_hospital uh on t.unidad_negocio=uh.hospital and uh.usuario='" . $_SESSION['id_usuario'] . "'
  where estatus = 1 and t.usuario_actual=$id";
  } */

$query = "select count(t.id_ticket) as total_activos
from tickets t
where estatus = 1 and categoria_2 in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "')and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')";

//echo $query;
$resultado = $mysqli->query($query);
$fila = $resultado->fetch_assoc();
$total_activos = $fila['total_activos'];



$query = "select count(t.id_ticket) as total_resueltos
from tickets t
where estatus = 2 and categoria_2 in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "') and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')";

//echo $query;
$resultado = $mysqli->query($query);
$fila = $resultado->fetch_assoc();
$total_resueltos = $fila['total_resueltos'];



$query = "select count(t.id_ticket) as total_cerrados
from tickets t
where estatus = 3 and categoria_2 in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "') and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')";

//echo $query;
$resultado = $mysqli->query($query);
$fila = $resultado->fetch_assoc();
$total_cerrados = $fila['total_cerrados'];



$graf1 = "";
$graf2 = "";
$anioGraf = date("Y");
$arrayG1 = array();
$arrayG2 = array();
$arrayStG1 = array();
$arrayStG2 = array();


$query = "select count(t.id_ticket) as total_activos, MONTH(t.fecha_registro) as mes
from tickets t
where t.fecha_registro between '" . $anioGraf . "-01-01 00:00:00' and '" . $anioGraf . "-12-31 23:59:59' and t.estatus='1' and categoria_2 in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "') and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')
group by MONTH(t.fecha_registro)
order by MONTH(t.fecha_registro)";


//echo $query;

$resultado = $mysqli->query($query);
while ($fila = $resultado->fetch_assoc()) {
    $arrayStG1 = array();
    $arrayStG1[0] = $fila['mes'];
    $arrayStG1[1] = $fila['total_activos'];
    array_push($arrayG1, $arrayStG1);
}

$contAux = 0;
for ($i = 1; $i <= 12; $i++) {
    if ($contAux < count($arrayG1)) {
        if ($arrayG1[$contAux][0] == $i) {
            $graf1 .= $arrayG1[$contAux][1];
            if ($i < 12) {
                $graf1 .= ",";
            }
            $contAux++;
        } else {
            $graf1 .= "0";
            if ($i < 12) {
                $graf1 .= ",";
            }
        }
    } else {
        $graf1 .= "0";
        if ($i < 12) {
            $graf1 .= ",";
        }
    }
}

/* * ************************************* */

$query = "select count(t.id_ticket) as total_resueltos_g2, MONTH(t.ultima_actualizacion) as mes
from tickets t

where t.estatus in (2,3)
and t.ultima_actualizacion between '" . $anioGraf . "-01-01 00:00:00' and '" . $anioGraf . "-12-31 23:59:59' and categoria_2 in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "')  and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')
group by MONTH(t.ultima_actualizacion)
order by MONTH(t.ultima_actualizacion)";



//echo $query;

$resultado = $mysqli->query($query);
while ($fila = $resultado->fetch_assoc()) {
    $arrayStG2 = array();
    $arrayStG2[0] = $fila['mes'];
    $arrayStG2[1] = $fila['total_resueltos_g2'];
    array_push($arrayG2, $arrayStG2);
}

$contAux = 0;
for ($i = 1; $i <= 12; $i++) {
    if ($contAux < count($arrayG2)) {
        if ($arrayG2[$contAux][0] == $i) {
            $graf2 .= $arrayG2[$contAux][1];
            if ($i < 12) {
                $graf2 .= ",";
            }
            $contAux++;
        } else {
            $graf2 .= "0";
            if ($i < 12) {
                $graf2 .= ",";
            }
        }
    } else {
        $graf2 .= "0";
        if ($i < 12) {
            $graf2 .= ",";
        }
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>

        <?php require 'resources/components/includes.php'; ?>

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

                $("#lnkAsignadosMi").click(function (e) {
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones-dashboard.php",
                        data: "opcion=subir_valiable_session&v_nv=3",
                        success: function (result) {
                            window.location.href = "tickets-preview.php";
                        }});
                });

                $("#lnkActivos").click(function (e) {
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones-dashboard.php",
                        data: "opcion=subir_valiable_session&v_nv=7",
                        success: function (result) {
                            window.location.href = "tickets-preview.php";
                        }});
                });

                $("#lnkPendientesAsig").click(function (e) {
                    $.ajax({
                        type: "POST",
                        url: "utils/funciones-dashboard.php",
                        data: "opcion=subir_valiable_session&v_nv=1",
                        success: function (result) {
                            window.location.href = "tickets-preview.php";
                        }});
                });
            });

            window.onload = function () {
                var ctx = document.getElementById('myChart');
                var mixedChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                                label: 'Ticket abiertos',
                                //backgroundColor: 'rgb(10, 99, 132,0.2)',
                                //borderColor: 'rgb(10, 99, 132)',
                                backgroundColor: "rgba(0,115,183,0.3)",
                                backgroundColorHover: "#0073b7",
                                data: [<?php echo $graf1; ?>],
                                type: 'bar'
                            }, {
                                label: 'Ticket cerrados',
                                //backgroundColor: 'rgb(255, 99, 132)',
                                //borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: "rgba(0,166,90,0.8)",
                                hoverBorderColor: "rgba(0,166,90,1)",
                                data: [<?php echo $graf2; ?>],

                                // Changes this dataset to become a line
                                type: 'line',
                                borderColor: "#00a65a",
                                fill: false
                            }],
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                    },
                    options: {
                        legend: {display: false}
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

        <input type="hidden" id="report-option" value="">
        <input type="hidden" id="report-restriccion" value="">

        <div class="content-wrapper" style="padding-bottom: 35px;">
            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1" style="margin-bottom: 70px;">
                <!-- Content Header (Page header) -->
                <section class="content-header no-pad-top">
                    <h3 class="no-margin">
                        Tablero de control
                    </h3>
                </section>
                <!-- Main content -->
                <section class="content" style="align-items: center;">
                    <div class="row">

                        <div class="col-lg-3 col-xs-6">
                            <a id="lnkActivos" href="#">
                                <div class="small-box bg-aqua-gradient">
                                    <div class="inner">
                                        <h3><?php echo $total_activos; ?></h3>
                                        <p>Activos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios-box"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!--div class="col-lg-3 col-xs-6">
                            <a id="lnkPendientesAsig" href="#">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3><?php //echo $total_pendientes_asignar;               ?></h3>
                                        <p>Pendientes de asignar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-social-buffer"></i>
                                    </div>
                                </div>
                            </a>
                        </div-->
                        <div class="col-lg-3 col-xs-6">
                            <a href="#">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3><?php echo $total_resueltos; ?></h3>
                                        <p>Resueltos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-done-all"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <a id="lnkAsignadosMi" href="#">
                                <div class="small-box bg-blue-gradient">
                                    <div class="inner">
                                        <h3><?php echo $total_cerrados; ?></h3>
                                        <p>Cerrados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-archive"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <!-- TABLE: LATEST ORDERS -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ultimos Reportes</h3>
                                    <div class="box-tools pull-right">
                                        <ul class="pagination pagination-sm inline">
                                            <li><a id="lnkAntUltTkts" href="#">«</a></li>
                                            <li><a id="lnkSigUltTkts" href="#">»</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%;"># Reporte</th>
                                                    <th style="width: 75%;">Categoría</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbdUltimosTkts" style="font-size: 0.8em;">
                                                <?php
                                                $query = "SELECT 
                                                            t.id_ticket,
                                                            DATE_FORMAT(t.fecha_registro, '%d/%m/%Y %H:%m:%s'),
                                                            CONCAT(
                                                                    CASE
                                                                        WHEN c2.nombre_area IS NOT NULL THEN CONCAT(' ', c2.nombre_area)
                                                                        ELSE ''
                                                                    END,
                                                                    CASE
                                                                        WHEN c4.nombre IS NOT NULL THEN CONCAT(' - ', c4.nombre)
                                                                        ELSE ''
                                                                    END) AS categoria
                                                        FROM
                                                            tickets t
                                                                LEFT JOIN
                                                            categoria c1 ON c1.id = t.categoria_1
                                                                LEFT JOIN
                                                            areas c2 ON c2.idareas = t.categoria_2
                                                                LEFT JOIN
                                                            categoria c3 ON c3.id = t.categoria_3
                                                                LEFT JOIN
                                                            categoria c4 ON c4.id = t.categoria_4
                                                        WHERE
                                                            t.estatus = 1 and c2.idareas in ('" . join("','", $_SESSION['usr_grupos']) . "') and unidad_negocio in ('" . join("','", $_SESSION['usr_hospitales']) . "')  and categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "')
                                                        ORDER BY t.fecha_registro DESC
                                                        LIMIT 10;";


                                                //echo $query;
                                                $resultado = $mysqli->query($query);
                                                $totalRegs = $resultado->num_rows;
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><a href='ticket.php?ticket=" . $fila['id_ticket'] . "'>" . $fila['id_ticket'] . "</a></td>";
                                                    echo "<td>" . $fila['categoria'] . "</td>";
                                                    echo "</tr>";
                                                }
                                                echo "<tr style='display:none;'><td><input id='hdnTieneMas' type='hidden' value='" . ($totalRegs == 10 ? 1 : 0) . "'></td></tr>";
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Reportes abiertos y resueltos <?php echo $anioGraf; ?></h3>
                                </div>
                                <div class="box-body">
                                    <div class="chart">
                                        <canvas id="myChart" style="height: 230px; width: 510px;" width="510" height="230"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>