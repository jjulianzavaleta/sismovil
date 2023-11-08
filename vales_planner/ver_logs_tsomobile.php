<?php

if( !isset($_GET['idvale']) ){
	die("Parametros invalidos");
}

include_once("../phps/conexion.php");
include_once("../phps/dvales_create.php");

$id_vale       = $_GET['idvale'];
$lastLog	   = getLogsTSMobile($id_vale);

if( empty($lastLog['tsomobile_fechaconsulta']) )die("No logs found");

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<br>
<div class="alert alert-primary" role="alert" style="text-align: center">
<b>TSO Mobile logs (Solo el último)</b>
</div>
<br><br>

<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Vale</th>
      <th scope="col">Algo fallo?</th>
	  <th scope="col">Triggered by job?</th>
	  <th scope="col">Fecha</th>
	  <th scope="col">Endpoint</th>
	  <th scope="col">Response</th>
    </tr>
  </thead>
  <tbody>
  
    <tr>
	
	<?php
		$i=1;
		
		echo '<tr>';
		echo '<th scope="row">'.$i.'</th>';
		echo '<td>'.$id_vale.'</td>';
		echo '<td>'.($lastLog['tsomobile_somethingwentwrong']=="1"?"Si":"No").'</td>';
		echo '<td>'.($lastLog['tsomobile_byjob']=="1"?"Si":"No").'</td>';
		echo '<td>'.date_format($lastLog['tsomobile_fechaconsulta'],"Y/m/d H:i:s").'</td>';
		echo '<td>'.$lastLog['tsomobile_endpoint'].'</td>';
		echo '<td>'.$lastLog['tsomobile_response'].'</td>';
		echo '</tr>';		
		
	?>
  </tbody>
</table>