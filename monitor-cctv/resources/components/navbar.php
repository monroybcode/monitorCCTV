<nav class="navbar navbar-default navbar-static-top menu-hsm" role="navigation" style="padding-left: 15px !important;">
    <!--
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"></span>
            </a>-->

    <!--<div class="collapse navbar-collapse" id="navbar-collapse">-->

    <?php
    $array_funciones = array();

    if (isset($_SESSION['funciones'])) {
        $array_funciones = $_SESSION['funciones'];

        if (in_array("ver_alta_ticket", $array_funciones)) {
            ?>
            <ul class="tabs tabs-horizontal nav navbar-nav navbar-left" style="background: #2e5596;">
                <li>
                    <a href="nuevo-ticket.php" class="link-item-list link-nuevo-ticket link-item-navbar" style="padding: 5px 15px; color: #FFF !important;">
                        <span class="fa fa-plus"></span>
                        <span>Nuevo Reporte</span>
                    </a>
                </li>
            </ul>
            <?php
        }
        ?>


        <?php
        if (in_array("ver_dashboard", $array_funciones)) {
            ?>
            <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">                
                <li>
                    <a href="tablero-control.php" class="link-item-list link-dashboard link-item-navbar" style="padding: 5px 15px;">
                        <span>Tablero de control</span>
                    </a>
                </li>
            </ul>
            <?php
        }
        ?>

        <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">                
            <li>
                <!--a href="<?php //echo $_SESSION['home'];     ?>" class="link-item-list link-principal link-item-navbar" style="padding: 5px 15px;"-->
                <a href="tickets-preview.php" class="link-item-list link-principal link-item-navbar" style="padding: 5px 15px;">
                    <span>Principal</span>
                </a>
            </li>
        </ul>


        <?php
        if (in_array("ver_resumen", $array_funciones)) {
            ?>

            <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">
                <li>
                    <a href="#" class="link-item-list link-item-navbar dropdown-toggle link-resumen" data-toggle="dropdown" aria-expanded="false"  style="padding: 5px 15px;">Resumen
                        <span class="fa fa-caret-down"></span></a>

                    <ul class="dropdown-menu" style="border: solid 1px #adadad;">
                        <li class="item-list-option">
                            <a href="resumen.php" class="link-item-list link-item-navbar link-actividad" style="padding: 5px 15px;">Actividad</a>
                        </li>

                        <li class="item-list-option">
                            <a href="estadisticas.php" class="link-item-list link-item-navbar link-estadisticas" style="padding: 5px 15px;">Estadísticas</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <?php
        }
        ?>





        <!--        <ul class = "tabs tabs-horizontal nav navbar-nav navbar-left">
                    <li class = "item-list-option">
                        <a href = "#" class = "link-item-list link-item-navbar link-reporte" style = "padding: 10px 15px;">Reportes</a>
                    </li>
                </ul>-->



        <?php
        if (in_array("ver_menu_administracion", $array_funciones)) {
            ?>
            <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">

                <li>
                    <a href="#" class="link-item-list link-item-navbar dropdown-toggle link-administracion" data-toggle="dropdown" aria-expanded="false"  style="padding: 5px 15px;">Administración
                        <span class="fa fa-caret-down"></span></a>
                    <ul class="dropdown-menu" style="border: solid 1px #adadad;">
                        <li class="item-list-option">
                            <a href="usuarios.php" class="link-item-list link-item-navbar link-usuarios">Usuarios</a>
                        </li>

                        <li class="item-list-option">
                            <a href="administracion_roles.php" class="link-item-list link-item-navbar link-roles">Roles</a>
                        </li>

                        <li class="item-list-option">
                            <a href="administracion_categorias.php" class="link-item-list link-item-navbar link-categorias">Categorias</a>
                        </li>


                        <li class="item-list-option">
                            <a href="reenvio_email.php" class="link-item-list link-item-navbar link-categorias">Reenviar Envios Erroneos</a>
                        </li>

                        <li class="item-list-option">
                            <a href="administracion_bitacora.php" class="link-item-list link-item-navbar link-categorias">Bitacora tickets</a>
                        </li>

                        <li class="item-list-option">
                            <a href="administracion_bitacora_email.php" class="link-item-list link-item-navbar link-categorias">Bitacora email</a>
                        </li>


                    </ul>
                </li>

            </ul>

            <?php
        }
        ?>

        <?php
    }
    ?>

    <ul class="nav navbar-nav navbar-right navbar-custom-menu">
        <!--li><a href="http://www.faveohelpdesk.com/demo/helpdesk/admin">Admin Panel</a></li-->

        <!-- START NOTIFICATION --> 
        <style type="text/css">
            @keyframes  spinner {
                to {transform: rotate(360deg);}
            }

            .spinner:before {
                content: '';
                box-sizing: border-box;
                position: absolute;

                left: 50%;
                width: 20px;
                height: 20px;
                margin-top: -10px;
                margin-left: -10px;
                border-radius: 50%;
                border: 2px solid #ccc;
                border-top-color: green;
                animation: spinner .6s linear infinite;
            }
        </style>


    </ul>

    <!--</div>-->
</nav>