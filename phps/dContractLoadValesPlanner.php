<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id',                'dt' => 0 ),
    array( 'db' => 'id',                'dt' => 1, 'original' => 'vv.id', 'type' => 'numeric' ),
    array( 'db' => 'fecha_registro',    'dt' => 2, 'type' => 'date'),
    array( 'db' => 'fecha_max_consumo', 'dt' => 3, 'type' => 'date'),
    array( 'db' => 'centro_costo',      'dt' => 4),
    array( 'db' => 'placa',             'dt' => 5),
    array( 'db' => 'usuario',           'dt' => 6),
    array( 'db' => 'estado_html',       'dt' => 7, 'method' => 'custom_vale'),
    array( 'db' => 'td_acciones',       'dt' => 8)
);

require('ssp.class.php');
require('conexion.php');
require('dvales_vale.php');

$sql_table_elements = SSP::simple( $_GET, $columns );

$data                       = getAllValesValeWeb($sql_table_elements);
$recordsFiltered            = getAllValesValeWeb_count($sql_table_elements)[0]['cantidad'];
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
