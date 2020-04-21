<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
?>
<?php
include '../utils/lib/nusoap.php';

if(!isset($_SESSION['id_usuario'])){
session_start();
}


$nip=0;
$email = "";
$name = 0;
$telefono = 0;
$usuario = "";
$RememberMe=false;
$UserName="";
$Password="";



$UrlWs = 'https://dominio-prueba.starmedica.com:8083/Sso2/ValidaLogIn.svc?singleWsdl';
if(isset($_POST['ps'])){
    $Password = $_POST['ps'];
}
if(isset($_POST['np'])){
    $nip = $_POST['np'];
}
if(isset($_POST['email'])){
    $email = $_POST['email'];
}
if(isset($_POST['name'])){
    $name = $_POST['name'];
}

if (isset($_POST['telefono'])) {

    $telefono = $_POST['telefono'];
}

if (isset($_POST['rm'])) {
    $RememberMe = $_POST['rm'];
}
if (isset($_POST['us'])) {
    $UserName = $_POST['us'];
}





 $username = "Inicio.18";
  $password = "540aa3661440699ae8cca4593d41ee8e812389ba";



   // $client = new nusoap_client('https://dominio-prueba.starmedica.com:8083/Sso2/ValidaLogIn.svc?singleWsdl','wsdl');
  $client = new nusoap_client('https://dominio-prueba.starmedica.com:8083/Sso2/ValidaLogIn.svc?Wsdl','wsdl');
    
 /*   $wsse_header = new WsseAuthHeader();

    $client->__setSoapHeaders(array($wsse_header));*/
$authHeaders = $client->getHeader();
$client->soap_defencoding = 'UTF-8';

$prefix = gethostname();
    $nonce = base64_encode( substr( md5( uniqid( $prefix.'_', true)), 0, 16));


$dateCreated = gmdate('Y-m-d\TH:i:s\Z');
        $dateExpires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U')+ (5 * 60));
  $haed='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsu:Timestamp wsu:Id="TS-'.$nonce.'"';
  $haed=$haed.'><wsu:Created>'.$dateCreated;
$haed=$haed.'</wsu:Created><wsu:Expires>'.$dateExpires;
$haed=$haed.'</wsu:Expires></wsu:Timestamp><wsse:UsernameToken wsu:Id="UsernameToken-'.$nonce.'"';
$haed=$haed.'><wsse:Username>'. $username.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$password.'</wsse:Password></wsse:UsernameToken></wsse:Security>';

//echo "-- ".$haed;

$client->setHeaders($haed);


      $client->setCredentials($username,$password,'basic');
    $err = $client->getError();
    if ($err) {
        echo 'Error en Constructor' . $err ;
    }

$model=['UserName'=>$UserName,'Password'=>$Password,'PasswordNuevo'=>'', 'PasswordNuevoConfirmado'=>'','RememberMe'=>$RememberMe, 'Nip' => $nip,'Sis'=>''];

    $param = array('model'=>$model,'Sis'=>'');

    $result = $client->call('LogInPHP',array($model));

    //  print_r($client->request);
    if ($client->fault) {
        print_r($result);
    } else {    // Chequea errores
         
        $err = $client->getError();
        if ($err) {     // Muestra el error
            echo 'Error' . $err ;
        } else {        // Muestra el resultado
         //print_r($result);
        $obj = json_decode($result['LogInPHPResult']['string'][0]);  
         
              if($obj->{'auth'}==true)
              {
                echo validaUS($obj->{'correo'});
             }else{
                echo  $result['LogInPHPResult']['string'][0];
              }
        }
    }




function validaUS($usuario){

}


?>