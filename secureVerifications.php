<?php

/*Verificaciones de la localizacion del usuario*/
include("geoiploc.php");
$ip = $_SERVER["REMOTE_ADDR"];//echo getCountryFromIP($ip, "AbBr");
if(getCountryFromIP($ip, "AbBr") != "PER" && getCountryFromIP($ip, "AbBr") != "ZZZ"){
    session_destroy();
    header("location: locationDenied.php");
}
/*------------------------------------------------*/

/*Verificaciones de la version de Internet Explorer*/
if(preg_match('/(?i)MSIE [5-8]/',$_SERVER['HTTP_USER_AGENT'])){
    ?>
    <div class="alert-error">
        <div class="row-fluid">
            <div class="span12" >
                <h6 style="text-align: center">La versión de Internet Explorer que se ejecuta no esta soportada,
                    se recomienda actualizarlo a la versióm mas reciente o iniciar desde otro navegador.<br>
                    Continue bajo su responsabilidad.
                </h6>
            </div>
        </div>
    </div>
<?php
}
/*------------------------------------------------*/

/*Verificaca si se trata de loguear desde un movil o tablet*/
/*include("Mobile_Detect.php");
$detect = new Mobile_Detect();
if($detect->isMobile() || $detect->isTablet()) {
    session_destroy();
    header("Location: deviceDenied.php");
}*/

/*Verificaciones la URL*/
/*------------------------------------------------*/
$current_url 		= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$supported_url 		= "testsismovil.chimuagropecuaria.com.pe/";

if( strpos($supported_url,$current_url) === false ){
	header("Location: http://".$supported_url);
}
