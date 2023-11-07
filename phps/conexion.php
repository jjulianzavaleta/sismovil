<?php

function conectarBD(){

	$serverName = "10.100.123.13";
	$connectionInfo = array( "Database"=>'db_sismovil', "UID"=>'dbamovil', "PWD"=>'.Lk4$B_x!{fgd=');
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( $conn ) {
		 
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}
	
	return $conn;

}


/**
 * @param $sql
 * @param $link
 * @param bool $onlyFirstRow
 * @return array|bool
 */
function queryBD($sql,&$link,$onlyFirstRow=false,$free_stmt=true,$show_errors=false){

    $data = array();
    $stmt = sqlsrv_query( $link, $sql );

    if( $stmt === false) {
        if($show_errors)
            print_r( sqlsrv_errors(), true) ;
		return false;
    }else{

        if($onlyFirstRow){
              $data = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);             
        }else {
           while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
    	   }
        }

        if($free_stmt)
            sqlsrv_free_stmt( $stmt);

        if($data === false)
            return array();
        else
            return $data;
    }
}

function startTransaction(&$link){
    if ( sqlsrv_begin_transaction( $link ) === false ) {
        die( print_r( sqlsrv_errors(), true ));
    }
}

function finishTransaction(&$link, $lastResult){

	if($lastResult === false){
        sqlsrv_rollback( $link );
        sqlsrv_close( $link );
		return false;
	}else{
        sqlsrv_commit( $link );
        sqlsrv_close( $link );
		return true;
	}
}




?>
