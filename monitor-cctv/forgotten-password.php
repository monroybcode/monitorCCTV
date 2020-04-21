<!DOCTYPE html>
<html>
    <head>
        <title>Recuperar Contraseña</title>
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
        <link href="resources/css/manager-login-style.css" rel="stylesheet">
        <!-- jQuery Bootstrap -->
        <script src="resources/js/jquery-3.1.0.min.js"></script>
        <!-- javascript Bootstrap -->
        <script src="resources/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#add_err").css('display', 'none', 'important');
                $("#login").click(function () {
                    username = $("#user").val();
                    email = $("#email").val();
                    $.ajax({
                        type: "POST",
                        url: "resources/controller/password-process.php",
                        data: "user=" + username + "&email=" + email,
                        success: function (html) {
                            if (html === 'true') {
                                $("#add_err").css('display', 'inline', 'important');
                                $("#add_err").html("<div class='success'>Tu nueva contraseña ha sido enviada a tu correo </div>");
                            } else if (html === 'nomail') {
                                $("#add_err").css('display', 'inline', 'important');
                                $("#add_err").html("<div class='error'>¡Lo sentimos! Servicio de envío de correo no disponible, inténtelo más tarde</div>");
                            }
                            else {
                                $("#add_err").css('display', 'inline', 'important');
                                $("#add_err").html("<div class='error'>¡Lo sentimos! Su usuario no se encuentra registrado</div>");
                            }
                        }
                    });
                    return false;
                });
            });
        </script>


    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <img class="logo_header" src="resources/images/logo-header.png" alt="Logo Star MÃ©dica">
            </div>

        </nav>

        <br/>
        <br/>
        <br/>
        <div class="container">
            <br>
            <br>
            <br>
            <br>
            <div class="welcome-subtitle">RECUPERA TU CONTRASEÑA</div>
            <div class="welcome-title">Ingresa tu número de empleado y correo electrónico
                <?php
                if (strcasecmp(substr($_SERVER["REQUEST_URI"], 0, 14), '/viaticostest/') == 0) {
                    echo '<div class="welcome-test">VERSIÓN DE PRUEBA</div>';
                }
                ?>
                <div id="admon-login" >

                    <form class="form-signin " role="form">
                        <div class="err" id="add_err"></div><br>
                        <input id="user" name="user" type="text" class="form-control" placeholder="Cuenta" required autofocus>
                        <br/>
                        <input id="email" name="email" type="text" class="form-control" placeholder="Correo Electrónico" required>
                        <br/>
                        <button class="login-button btn-block"  id="login" type="submit">
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> REINICIAR CONTRASEÑA
                        </button>
                    </form>
                </div>
                <br>
                <br>
                <a href="index.php" class="forgotten"> 
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> VOLVER AL INICIO
                </a> 

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
