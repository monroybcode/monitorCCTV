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
    session_start();

    require('../resources/connection/conexion.php');
    $mysqli->query("SET NAMES 'utf8'");
    

  
    $sql = "SELECT * FROM usuarios WHERE lower(login)=lower('" . $usuario . "') and ind_activo=1 limit 1";
    //echo $sql;
    $resultado = $mysqli->query($sql);
    $num_filas = $resultado->num_rows;
    
    $fila = $resultado->fetch_assoc();
    
    if ($num_filas == 1) {
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['usr_email'] = $fila['email'];
        $_SESSION['puesto'] = $fila['puesto'];
    
        $arrayFunciones = array();
        $arrayFuncionesUsuario = array();
        $array_grupos = array();
        $array_categorias_usr = array();
        $array_hospitales = array();
        $array['existe'] = true;
    
        $sqlFuncionesR = "select f.nombre "
                . "from funciones f "
                . "inner join rol_funciones rf on f.id_funcion=rf.id_funcion "
                . "where rf.id_rol='" . $fila['rol'] . "' ";
        $resultadoFuncionesR = $mysqli->query($sqlFuncionesR);
        while ($filaFuncionesR = $resultadoFuncionesR->fetch_assoc()) {
            array_push($arrayFunciones, $filaFuncionesR['nombre']);
        }
    
        $sqlFuncionesU = "select f.nombre "
                . "from funciones f "
                . "inner join usuario_funciones uf on f.id_funcion=uf.id_funcion "
                . "where uf.id_usuario='" . $fila['id_usuario'] . "' ";
        $resultadoFuncionesU = $mysqli->query($sqlFuncionesU);
        while ($filaFuncionesU = $resultadoFuncionesU->fetch_assoc()) {
            array_push($arrayFuncionesUsuario, $filaFuncionesU['nombre']);
        }
    
    
    
        $sql = "select descripcion from catalogo_valor where id='" . $fila['rol'] . "' and catalogo='1';";
        $resultado = $mysqli->query($sql);
        $filaR = $resultado->fetch_assoc();
    
        $sqlGrupos = "select id_grupo from usuario_grupos where id_usuario = '" . $fila['id_usuario'] . "'";
        $resultadoGrupos = $mysqli->query($sqlGrupos);
        while ($filaGrupos = $resultadoGrupos->fetch_assoc()) {
            array_push($array_grupos, $filaGrupos['id_grupo']);
        }
    
        $sqlCategorias = "select id_categoria from usuario_categorias where id_usuario = '" . $fila['id_usuario'] . "'";
        $resultadoCategorias = $mysqli->query($sqlCategorias);
        while ($filaCategorias = $resultadoCategorias->fetch_assoc()) {
            array_push($array_categorias_usr, $filaCategorias['id_categoria']);
        }
    
        $sqlhospitales = "select hospital from usuario_hospital where usuario = '" . $fila['id_usuario'] . "'";
        $resultadohospitales = $mysqli->query($sqlhospitales);
        while ($filahospitales = $resultadohospitales->fetch_assoc()) {
            array_push($array_hospitales, $filahospitales['hospital']);
        }
    
        $_SESSION['nv'] = '7';
        $_SESSION['nombre_rol'] = $filaR['descripcion'];
        $_SESSION['funciones'] = $arrayFunciones;
        $_SESSION['usr_funciones'] = $arrayFuncionesUsuario;
        $_SESSION['usr_grupos'] = $array_grupos;
        $_SESSION['usr_categorias'] = $array_categorias_usr;
        $_SESSION['usr_hospitales'] = $array_hospitales;
    
    
        if (in_array("ver_dashboard", $arrayFunciones)) {
            $_SESSION['home'] = "tablero-control.php";
        } else {
            $_SESSION['home'] = "tickets-preview.php";
        }
    
        $array['home'] = $_SESSION['home'];
    
        $sql = "update usuarios set ultima_visita=now() where id_usuario='" . $fila['id_usuario'] . "'";
        $mysqli->query($sql);
    
        $array['existe'] = true;
        $array['Rurl'] = $_SESSION['home'];
        echo json_encode($array);
    } else {
        $array['existe'] = false;
        $array['Rurl'] = '';
        echo json_encode($array);
    }
}


?>