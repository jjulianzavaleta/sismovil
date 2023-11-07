<?php

class ProjectManager
{
  public static function rootDirectory(){
    return str_replace('\\', '/', dirname(__FILE__, 2));
  }
  
  public static function projectURL(){
	return "http://sismovil.chimuagropecuaria.com.pe";
  }

  public static function adminEmail(){
    return "lvera@chimuagropecuaria.com.pe";
  }

  public static function legalEmail(){
    return "cpenav@chimuagropecuaria.com.pe";
  }

  public static function logisticaEmail(){
    return "amejia@chimuagropecuaria.com.pe";
  }
}

?>
