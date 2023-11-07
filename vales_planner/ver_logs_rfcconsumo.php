<?php

if( !isset($_GET['idvale']) ){
	die("Parametros invalidos");
}

include_once("../phps/conexion.php");
include_once("../phps/dvales_RFCConsumoVales.php");

$id_vale       = $_GET['idvale'];
$historial	   = getHistorialRFCConsumo($id_vale);

if( empty($historial) )die("No logs found");

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<br>
<div class="alert alert-primary" role="alert" style="text-align: center">
<b>HISTORIAL DE ACTUALIZACIONES RFC</b>
</div>
<br><br>

<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">ID</th>
      <th scope="col">Vale</th>
	  <th scope="col">Fecha</th>
	  <th scope="col">RFC</th>
	  <th scope="col">By Job</th>
	  <th scope="col">REQUEST DATA</th>
	  <th scope="col">RESPONSE</th>
	  <th scope="col">ESTADO</th>
    </tr>
  </thead>
  <tbody>
  
    <tr>
	
	<?php
		$i=1;
		foreach($historial as $item){
			echo '<tr>';
			echo '<th scope="row">'.$i.'</th>';
			echo '<td>'.$item['id'].'</td>';
			echo '<td>'.$item['idvale'].'</td>';
			echo '<td>'.date_format($item['fecha'],"Y/m/d H:i:s").'</td>';
			echo '<td>'.$item['rfc'].'</td>';
			echo '<td>'.($item['byjob']==1?"Si":"No").'</td>';
			echo '<td>'.$item['request'].'</td>';
			echo '<td>'.$item['response'].'</td>';
			echo '<td>'.($item['success']=="1"?"Exito":"Error").'</td>';
			echo '</tr>';
			$i++;
		}
	?>
  </tbody>
</table>