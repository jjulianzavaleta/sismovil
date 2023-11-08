<?php
	$user_anula = getAdminById($data_contract['anulado_usuario']);
	
	$posicion_arroba = strpos($user_anula[0]['usuario'],'@');
	if( $posicion_arroba === false ){
		$user_anula = $user_anula[0]['usuario'];
	}else{
		$user_anula = substr($user_anula[0]['usuario'], 0, $posicion_arroba);
	}	
	
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr align="center" width="100%">
		<td align="center" width="100%">
			<span class='label' style="background-color: red !important;">SOLICITUD DE CONTRATO ANULADA</span>
		</td>
	</tr>
	<tr align="center" width="100%">
		<td align="center" width="100%">
			<span class='label' style="background-color: red !important;">Usuario: <?=$user_anula?></span>		
			<span class='label' style="background-color: red !important;">Fecha: <?=date_format($data_contract['anulado_fecha'],"Y/m/d H:i:s")?></span>
		</td>
	</tr>
    <tr>
        <td align="center" width="100%">
            <span class="label" style="background-color: red !important;">Razón: <?=$data_contract['anulado_razon']?></span>
        </td>
    </tr>
</table>