<!DOCTYPE html>
<?php include 'utils/constantes.php'; ?>
<html>
    <head>
        <title><?php echo NOMBRE_SISTEMA; ?></title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="author" content="GSM" />

        <link rel="icon" href="resources/images/star-icon.ico">
        <!-- Bootstrap core CSS -->
        <link href="resources/css/bootstrap.min.css" rel="stylesheet">
        <link href="resources/css/bootstrap.css" rel="stylesheet">
        <!-- Bootstrap footer -->
        <link href="resources/css/sticky-footer-navbar.css" rel="stylesheet">
        <link href="resources/css/header-footer-style.css" rel="stylesheet">
        <!-- Custome style -->
        <link href="resources/css/manager-login-style.css?1.0.1" rel="stylesheet">
        <!-- jQuery Bootstrap -->
        <script src="resources/js/jquery-3.1.0.min.js"></script>
        <!-- javascript Bootstrap -->
        <script src="resources/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                var device = navigator.userAgent;

                if (device.match(/Iphone/i)
                        || device.match(/Ipod/i)
                        || device.match(/Android/i)
                        || device.match(/J2ME/i)
                        || device.match(/BlackBerry/i)
                        || device.match(/iPhone|iPad|iPod/i)
                        || device.match(/Opera Mini/i)
                        || device.match(/IEMobile/i)
                        || device.match(/Mobile/i)
                        || device.match(/Windows Phone/i)
                        || device.match(/windows mobile/i)
                        || device.match(/windows ce/i)
                        || device.match(/webOS/i)
                        || device.match(/palm/i)
                        || device.match(/bada/i)
                        || device.match(/series60/i)
                        || device.match(/nokia/i)
                        || device.match(/symbian/i)
                        || device.match(/HTC/i)) {
                    window.location.href = "mobile/index.php";
                }

                $("#btn-inicia-session").click(function (e) {
                    e.preventDefault();
                    var data = $("#frm-login").serialize();

                    $.ajax({
                        url: "resources/controller/controller-login.php",
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $("#add_err").html("<div class='success'><img src='resources/images/loading.gif' alt='Logo Star Médica'> Cargando... </div>");
                            $("#add_err").css('display', 'block', 'important');
                        },
                        success: function (data, textStatus, jqXHR) {
                            console.log(data);

                            if (data.existe === true) {
                                window.location.href = data.home;
                            } else {
                                $("#add_err").css('display', 'inline', 'important');
                                $("#add_err").html("<div class='error'>Usuario o Contraseña Errónea</div>");
                                $("#btn-inicia-session").removeAttr("disabled");
                                $("#btn-inicia-session").html('Entrar');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                            console.log(errorThrown);
                            console.log(jqXHR);
                        }
                    });

                });
            });
        </script>
    </head>
    <body>
        <img class="login-logo"  src="resources/images/logo.png" alt="Logo Star Médica">
        <img class="header-login" src="resources/images/header-login.jpg" alt="Logo Star Médica">
        <div class="container-transparent">

            <div class="container-form">
                <div class="welcome-subtitle"><?php echo NOMBRE_SISTEMA; ?></div>
                <?php
                if (strcasecmp(substr($_SERVER["REQUEST_URI"], 0, 14), '/test/') == 0) {
                    echo '<div class="welcome-test">VERSIÓN DE PRUEBA</div>';
                }
                ?>
                <div class="welcome-message">Bienvenido</div>
                <!--button class="login-option btn-block"  id="login-google" onclick="login()">
                     Iniciar sesión con Google
                </button-->
                <button class="login-option second-button btn-block"  id="login-number"  data-toggle="modal" data-target="#loginModal">
                    Iniciar sesión
                </button>

            </div>
        </div>


        <div id="loginModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </button>
                        <h4 class="modal-title">Iniciar sesión</h4>
                    </div>
                    <div class="modal-body">
                        <div class="" id="login-user-number">
                            <div class="welcome-title"><br>Ingrese su cuenta y contraseña 
                                <br>para continuar.</div>
                            <div id="admon-login" >

                                <form id="frm-login" class="form-signin " role="form" >
                                    <div class="err" id="add_err"></div>
                                    <input id="usuario" name="usuario" type="text" class="form-control" placeholder="Cuenta" required autofocus>
                                    <br/>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña" required>

                                    <button class="login-button btn-block"  id="btn-inicia-session" type="submit" onclick="this.disabled = 1;this.firstChild.data = 'Entrando...'">Entrar</button>
                                </form>
                            </div>
                            <br> 
                            <a href="forgotten-password.php" class="forgotten">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> ¿Olvidaste tu contraseña?
                            </a>
                            <br><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn login-button" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div id="adn"></div>
            <div class="grey-space">
                <img src="resources/images/espacio-humano.png" class="slogan-space-manager" alt=""/>
            </div>
        </div>

    </body>
</html>
