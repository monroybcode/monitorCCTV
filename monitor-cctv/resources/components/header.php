<header class="main-header">

    <a href="<?php echo $_SESSION['home']; ?>" class="logo">
        <span class="logo-mini"><img src="resources/images/logo-star-small.png" class="img-sidebar-hide"/></span>
        <span class="logo-lg"><b>Service</b>Desk</span>
    </a>

    <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"></span>
        </a>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">
                <li>
                    <a href="nuevo-ticket.php" class="link-item-list">
                        <span class="fa fa-plus"></span>
                        <span>Nuevo Evento</span> 
                    </a>
                </li>
            </ul>
            <?php
            $array_funciones = array();

            if (isset($_SESSION['funciones'])) {
                $array_funciones = $_SESSION['funciones'];

                if (in_array("ver_menu_administracion", $array_funciones)) {
                    ?>
                    <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">

                        <li>
                            <a href="#" class="link-item-list dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Administración
                                <span class="fa fa-caret-down"></span></a>
                            <ul class="dropdown-menu" style="border: solid 1px #adadad;">

                                <li class="item-list-option">
                                    <a href="bitacora.php" class="link-item-list">Bitácora</a>
                                </li>

                                <li class="item-list-option">
                                    <a href="tablero-control.php" class="link-item-list">Reportes</a>
                                </li>

                                <li class="item-list-option">
                                    <a href="tickets-usuarios.php" class="link-item-list">Reportes por usuario</a>
                                </li>


                                <li class="item-list-option">
                                    <a href="usuarios.php" class="link-item-list">Usuarios</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
        <?php
    }
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
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" ng-click="fbNotify()" title="Notificaciones">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning ng-binding" ng-bind="notiNum">2</span>
                    </a>
                    <ul class="dropdown-menu" style="background: #fff;border: 1px solid rgba(100, 100, 100, .4);border-radius: 0 0 2px 2px;box-shadow: 0 3px 8px rgba(0, 0, 0, .25);color: #1d2129;overflow: visible;position: absolute !important;width: 430px !important;z-index: -1;">
                        <li class="header"><div class="cool" style="width: 50%;"><b>Notificaciones</b></div></li>
                        <li>
                            <!-- inner menu: contains the actual data -->

                            <div>
                                <ul class="list-group" style="outline: none;overflow-x: hidden;overflow-y: auto; height: 425px;margin-bottom: 0px;">
                                    <li class="row list-group-item" ng-hide="showing" style="padding :35px;">
                                        <div class="spinner">

                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!--div>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-share text-bold"></i> Anulaci�n de factura
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-share text-bold"></i> Paquetes - Alta, baja y modificaci�n 
                                        </a>
                                    </li>
                                </ul>
                            </div-->

                        </li>

                    </ul>
                </li>

                <!-- END NOTIFICATION --> 
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="resources/images/logo-star-small.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <!--img src="resources/images/logo-star-small.png" class="img-circle" alt="User Image"-->

                            <p>
                                <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                                <small><span class="hidden-xs"><?php echo $_SESSION['nombre_rol']; ?></span></small>
                            </p>
                        </li>
                        </li-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <!--a href="#" class="btn btn-default btn-flat">Mi cuenta</a-->
                            </div>
                            <div class="pull-right">
                                <a href="resources/controller/controller-logout.php" class="btn btn-default btn-flat">
                                    <span class="fa fa-sign-out"></span>
                                    <span>Salir</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>
    </nav>
</header>