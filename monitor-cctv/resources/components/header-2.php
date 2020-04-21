<!--<header class="main-header">-->
<!--<div class="col-lg-12">-->
<div class="header-title-content">
    <!--div class="header-title-2" style="float: left">
        <div>
            <a href="<?php echo $_SESSION['home']; ?>"><img src="resources/images/inicio.png" style="width: 24px;" alt=""></a>
            <span class="titulo-sistema" style="vertical-align: bottom;"><?php echo NOMBRE_SISTEMA ?></span>
            <input type="hidden" id="id_usuario_logueado" value="<?php echo $_SESSION['id_usuario']; ?>">
        </div>

        <span class="titulo-puesto-trabajo">
    <?php echo $_SESSION['nombre']; ?>
        </span>

        <input type="hidden" id="nombre_usuario_logueado" value="<?php echo $_SESSION['nombre']; ?>">

    </div>
    <div class="header-title-3" style="float: right;">
        <img src="resources/images/logo-header.png" class="header-logo-chico" alt=""/> 
        <div style="text-align: right;margin: 0px 0px 0 0;">
            <a href="myuser.php" class="titulo-sistema"><img src="resources/images/configuracion.png" style="width: 24px;" alt="Cambiar contraseña"/></a>
            <a href="resources/controller/controller-logout.php" class="titulo-sistema"><img src="resources/images/salir.png" style="width: 24px;" alt="Salir"/></a>
        </div>
    </div-->

    <div class="row" style="margin-top: 5px;margin-bottom: 5px;">
        <div class="col-lg-6">
            <div class="col-lg-3" style="padding-left: 5px !important;">
                <img src="resources/images/logo-header.png" class="header-logo-chico" alt=""/>
            </div>
            <div class="col-lg-9" style="padding-left: 30px;color: #98999a;">
                <div style="padding-top: 19px;">
                    <span style="font-family: HelveticaNeue !important;font-size: 15pt;letter-spacing: 2px;"><?php echo NOMBRE_SISTEMA ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="nav-usr-prf pull-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <input type="hidden" id="id_usuario_logueado" value="<?php echo $_SESSION['id_usuario']; ?>">
                        <input type="hidden" id="nombre_usuario_logueado" value="<?php echo $_SESSION['nombre']; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding-top: 24px;padding-bottom: 0px;">
                            <span class="hidden-xs" style="font-family: HelveticaNeue !important;color: #98999a;"><?php echo $_SESSION['nombre']; ?></span>
                            <i class="fa fa-user-circle margin-r-5" style="font-size: 1.3em;"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" style="z-index: 1001; width: 160px !important; margin-right: 15px;font-family: HelveticaNeue !important;">
                            <li class="user-footer">
                                <div style="margin-bottom: 2px;">
                                    <a href="myuser.php" class="btn btn-default btn-flat" style="width: 100%;">Perfil</a>
                                    <!--a href="myuser.php" class="titulo-sistema"><img src="resources/images/configuracion.png" style="width: 24px;" alt="Cambiar contraseña"/></a-->
                                </div>
                                <div>
                                    <a href="resources/controller/controller-logout.php" class="btn btn-default btn-flat" style="width: 100%;">Salir</a>
                                    <!--a href="resources/controller/controller-logout.php" class="titulo-sistema"><img src="resources/images/salir.png" style="width: 24px;" alt="Salir"/></a-->
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>


</div>


<!--</div>-->
<!--</header>-->