<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-left: 15px !important;">
    <nav class="sub-nav">
        <div class="container-fluid container-sub-nav no-padding">
            <ul class="nav navbar-nav nav-filtros-ticket">
                <?php
                if (isset($_SESSION['funciones'])) {
                    $array_funciones = $_SESSION['funciones'];

                    if (in_array("ver_tickets_sin_asignar", $array_funciones)) {
                        ?>
                       <!-- <li <?php
                        if ($_SESSION['nv'] == '1') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '1', 'En grupo');" class="sub-nav-options">En grupo</a></li>-->

                        <?php
                    }

                    if (in_array("ver_tickets_reportados_por_mi", $array_funciones)) {
                        ?>
                        <li <?php
                        if ($_SESSION['nv'] == '2') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '2', 'Reportados por mi');" class="sub-nav-options">Reportados por mi</a></li>

                        <?php
                    }

                    if (in_array("ver_tickets_asignado_a_mi", $array_funciones)) {
                        ?>
                        <li <?php
                        if ($_SESSION['nv'] == '3') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '3', 'Asignadas a mi');" class="sub-nav-options">Asignadas a mi</a></li>

                        <?php
                    }

                    if (in_array("ver_tickets_reportados_por_mi_area", $array_funciones)) {
                        ?>
                       <!-- <li <?php
                        if ($_SESSION['nv'] == '6') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '6', 'Reportados por mi grupo');" class="sub-nav-options">Reportados por mi grupo</a></li>-->

                        <?php
                    }

                    if (in_array("ver_tickets_asignados_a_mi_area", $array_funciones)) {
                        ?>
                      <!--  <li <?php
                        if ($_SESSION['nv'] == '4') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '4', 'Asignadas a mi grupo');" class="sub-nav-options">Asignadas a mi grupo</a></li>-->

                        <?php
                    }

                    if (in_array("ver_tickets_resueltos_por_mi", $array_funciones)) {
                        ?>
                        <li <?php
                        if ($_SESSION['nv'] == '5') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '5', 'Resueltas por mi');" class="sub-nav-options">Resueltas por mi</a></li>

                        <?php
                    }

                    if (in_array("ver_tickets_todos", $array_funciones)) {
                        ?>
                        <li <?php
                        if ($_SESSION['nv'] == '7') {
                            echo 'class="active"';
                        }
                        ?> ><a href="#" onclick="muestra_tickets_cat(this, '7', 'Todos');" class="sub-nav-options">Ver todos</a></li>

                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </nav>
</div>