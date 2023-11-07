<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr align="center" width="100%">
		<td align="center" width="100%">
			<span class='label' style="background-color: red !important;">VALE ANULADO</span>
		</td>
	</tr>
	<?php
		if( $data_vale['usuario_anula'] === "9999" || empty($data_vale['usuario_anula']) ){
			$usuario_anula = "Automático";
		}else{
			$usuario_anula = getUsernameFromUsuarioActiveDirectory($data_vale['usuario_anula']);
		}
	?>
	<tr align="center" width="100%">
		<td align="center" width="100%">			
			<span class='label' style="background-color: red !important;">Usuario: <?=$usuario_anula?></span>
			
			<?php if( !empty($data_vale['fecha_anula']) ){ ?>
			<span class='label' style="background-color: red !important;">Fecha: <?=date_format($data_vale['fecha_anula'], 'Y-m-d H:i:s')?></span>
			<?php } ?>
		</td>
	</tr>
</table>