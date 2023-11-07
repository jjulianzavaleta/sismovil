<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'idcontrato',                'dt' => 0 ),
    array( 'db' => 'datosgenerales_codigo',     'dt' => 1 ),
    array( 'db' => 'fecha_formateada',          'dt' => 2, 'original' => 'cs.datosgenerales_fecharegistra'),
    array( 'db' => 'nombre_empresa',            'dt' => 3, 'original' => 'ce.descripcion' ),
    array( 'db' => 'nombre_proveedor',          'dt' => 4, 'original' => 'cp.razon_social' ),
    array( 'db' => 'nombre_tipocontrato',       'dt' => 5, 'original' => 'tc.descripcion' ),
    array( 'db' => 'estado_html',               'dt' => 6 ),
    array( 'db' => 'td_acciones',               'dt' => 7 )
);

require('ssp.class.php');
require( 'conexion.php');
require('dcontract_contratos.php');
require('dcontract_reportes.php');

$sql_table_elements = SSP::simple( $_GET, $columns );

$ROLE_RESPONSABLE_AREA = 2;
$data                       = getAllContratosToApproveVistaMiArea($ROLE_RESPONSABLE_AREA, $_GET['sp_filter_idarea'], $sql_table_elements);
$recordsFiltered            = getAllContratosToApproveVistaMiArea_count($_GET['sp_filter_idarea'], $sql_table_elements)[0]['cantidad'];
$recordsTotal               = $recordsFiltered;

$response =  array(
    "draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
    "recordsTotal"    => intval( $recordsTotal ),
    "recordsFiltered" => intval( $recordsFiltered ),
    "data"            => SSP::data_output( $columns, $data )
);

echo json_encode(
    $response
);
