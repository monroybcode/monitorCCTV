<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <div>
            <div style="float: left; height: 67px; padding: 7px;">
                <div>
                    <a href="<?php echo $_SESSION['home']; ?>" class="titulo-sistema"><span class="fa fa-home"></span></a>
                    <span class="titulo-sistema" style="vertical-align: bottom;"><b><?php echo NOMBRE_SISTEMA ?></b></span>
                    <br>
                    <span class="titulo-sistema">
                        <?php echo $_SESSION['nombre']; ?>
                    </span>
                </div>
                <span class="titulo-puesto-trabajo"></span>
            </div>
        </div>
        <div class="user-panel" style="color: #4b646f; background: #4c4c4c">

        </div>

        <ul class="sidebar-menu">

            <?php
            $array_funciones = array();

            if (isset($_SESSION['funciones'])) {
                $array_funciones = $_SESSION['funciones'];
            }

            if (in_array("ver_reportados_por_mi", $array_funciones)) {
                $sqlRpm = "select count(id_ticket) as reportados from tickets where usuario_registra='" . $_SESSION['rol'] . "';";
                $resultadoRpm = $mysqli->query($sqlRpm);
                $filaRpm = $resultadoRpm->fetch_assoc();
                $reportados_por_mi = $filaRpm['reportados'];

                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=1" class="link-item-list">Reportados por mi <span class="badge icon-item-list pull-right">' . $reportados_por_mi . '</span></a>';
                echo '</li>';
            }


            if (in_array("ver_asignados_a_mi", $array_funciones)) {
                $sqlAam = "select count(id_ticket) as asignados_a_mi from tickets where usuario_actual='" . $_SESSION['rol'] . "';";
                $resultadoAam = $mysqli->query($sqlAam);
                $filaAam = $resultadoAam->fetch_assoc();
                $asignados_a_mi = $filaAam['asignados_a_mi'];



                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=2" class="link-item-list">Asignados a mi <span class="badge icon-item-list pull-right">' . $asignados_a_mi . '</span></a>';
                echo '</li>';
            }


            if (in_array("ver_no_asignados", $array_funciones)) {
                $no_asignados = "0";

                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=3" class="link-item-list">No asignados <span class="badge icon-item-list pull-right">' . $no_asignados . '</span></a>';
                echo '</li>';
            }

            if (in_array("ver_generados_en_mi_area", $array_funciones)) {
                $generados_en_mi_area = "0";

                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=4" class="link-item-list">Generados en mi área <span class="badge icon-item-list pull-right">' . $generados_en_mi_area . '</span></a>';
                echo '</li>';
            }

            if (in_array("ver_pendientes_de_mi_accion", $array_funciones)) {
                $pendientes_de_mi_accion = "0";

                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=5" class="link-item-list">Pendientes de mi acción <span class="badge icon-item-list pull-right">' . $pendientes_de_mi_accion . '</span></a>';
                echo '</li>';
            }

            if (in_array("ver_resueltas_por_mi", $array_funciones)) {
                $resueltas_por_mi = "0";

                echo '<li class="item-list-option">';
                echo '<a href="tickets.php?nv=6" class="link-item-list">Resueltas por mi <span class="badge icon-item-list pull-right">' . $resueltas_por_mi . '</span></a>';
                echo '</li>';
            }
            ?>






        </ul>
    </section>

</aside>