<?php

function getCerrarValesVencidos(){
	
	 $sql = "update vales_vale set anulado = 1, usuario_anula = 9999, fecha_anula = GETDATE()
			 where id in (
				select v.id
				from vales_vale v
				where v.anulado = 0 and v.estado = 2
					  and GETDATE() > v.fecha_max_consumo 
				)";

    $link = conectarBD();
    $data = queryBD($sql,$link);
	
	return $data;
	 
}