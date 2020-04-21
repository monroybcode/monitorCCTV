<?php
include '../resources/connection/conexion.php';
include "../utils/constantes.php";
$mysqli->query("SET NAMES 'UTF8'");
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else
    $email = '';
?>

<!DOCTYPE html>
<html>
    <head>

        <title>Star Médica | Login Mobile</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
        <meta name="author" content="GSM" />

        <?php include '../resources/components/includes-mobile.php'; ?>

        <script type="text/javascript">
            function logout() {
                gapi.auth.signOut();
                location.reload();
            }

            function login() {
                var myParams = {
                    'clientid': '502896857984-egkcit4uok6ueouot1fkhisus9ff0lg5.apps.googleusercontent.com',
                    'cookiepolicy': 'single_host_origin',
                    'callback': 'loginCallback',
                    'approvalprompt': 'force',
                    'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
                };
                gapi.auth.signIn(myParams);
            }


            function loginCallback(result) {
                if (result['status']['signed_in']) {
                    var request = gapi.client.plus.people.get({
                        'userId': 'me'
                    });
                    request.execute(function (resp) {
                        var email = '';
                        if (resp['emails']) {
                            for (i = 0; i < resp['emails'].length; i++) {
                                if (resp['emails'][i]['type'] == 'account') {
                                    email = resp['emails'][i]['value'];
                                }
                            }
                        }
                        //console.log(resp);
                        var str = "Name:" + resp['displayName'] + "<br>";
                        str += "Image:" + resp['image']['url'] + "<br>";
                        str += "<img src='" + resp['image']['url'] + "' /><br>";

                        str += "URL:" + resp['url'] + "<br>";
                        str += "Email:" + email + "<br>";

                        window.location.href = "resources/controller/sessionmobile.php?email=" + email;
                    });
                }
            }

            function onLoadCallback() {
                gapi.client.load('plus', 'v1', function () {});
            }

        </script>

        <script type="text/javascript">
            (function () {
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.async = true;
                po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();
        </script>


    </head>
    <body>
        <div class="profile-head ">
            <img class="ima-star" src="../resources/images/starmedica.png"/>

            <div class="top-message">
                <?php echo NOMBRE_SISTEMA; ?>
            </div>
        </div>


        <div class="grey-back">
            <div class="col-md-3">
                <br>

                <div id="admon-login" style="margin:0 2%">
                    <form class="form-signin " role="form"   >
                        <h4>INSTRUCCIONES</h4><br>
                        <p id="log_text"> 
                            <span style="font-weight: bold;"> 1.</span> Presiona el boton Iniciar sesi&oacute;n.<br>
                            <span style="font-weight: bold;">  2.</span> Te pedira tu correo y contrase&ntilde;a de @starmedica.com<br>

                            <span style="font-weight: bold;">  3.</span> Debes aceptar los permisos que te pide, para continuar<br>
                        </p>    
                        <br>
                        <button type="button" class="btn btn-success btn-lg btn-block" onClick="login()" style="padding: 20px" >Iniciar Sesi&oacute;n</button>
                        <p style="color: #ea1010; font-size: 14px"><?php echo $email ?></p>


                    </form>
                </div>
                <br> 

                <br>
                <br>
                <br>
            </div>
            <div class="footer">
                <div id="adn"></div>
                <div class="grey-space">
                    <img src="../resources/images/espacio-humano.png" class="slogan-left" alt=""/>
                </div>
            </div>
        </div>









    </body>
</html>
