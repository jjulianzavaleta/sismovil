<?php

if( !isset($_GET['idvale']) ){
	die("Parametros invalidos");
}

include_once("../phps/conexion.php");
include_once("../phps/dvales_create.php");

$id_vale       = $_GET['idvale'];
$historial_kiloemtraje = getHistorialKilometraje($id_vale);
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<br>
<div class="alert alert-primary" role="alert" style="text-align: center">
<b>HISTORIAL DE ACTUALIZACIONES</b>
</div>
<br><br>

<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">ID</th>
      <th scope="col">Vale</th>
      <th scope="col">Usuario</th>
	  <th scope="col">Fecha</th>
	  <th scope="col">Vale - Valor antes</th>
	  <th scope="col">Vale - Valor despues</th>
	  <th scope="col">Obs - Valor antes</th>
	  <th scope="col">Obs - Valor despues</th>
	  <th scope="col">Equipo - ¿Valor fue actualizado?</th>
    </tr>
  </thead>
  <tbody>
  
    <tr>
	
	<?php
		$i=1;
		foreach($historial_kiloemtraje as $item){
			echo '<tr>';
			echo '<th scope="row">'.$i.'</th>';
			echo '<td>'.$item['id'].'</td>';
			echo '<td>'.$item['idvale'].'</td>';
			echo '<td>'.getUsernameFromUsuarioActiveDirectory($item['usuario']).'</td>';
			echo '<td>'.$item['fecha'].'</td>';
			echo '<td>'.$item['vale_valor_old'].'</td>';
			echo '<td>'.$item['vale_valor_new'].'</td>';
			echo '<td>'.$item['vale_obs_old'].'</td>';
			echo '<td>'.$item['vale_obs_new'].'</td>';
			echo '<td>'.($item['was_equipo_valor_updated']=="1"?"Si":"No").'</td>';
			echo '</tr>';
			$i++;
		}
	?>
  </tbody>
</table>