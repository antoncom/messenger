<?php
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'modx_promocodes';

// Table's primary key
$primaryKey = 'pa_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
	array(
		'db' => 'pa_id',
		'dt' => 'DT_RowId',
		'formatter' => function( $d, $row ) {
			// Technically a DOM id cannot start with an integer, so we prefix
			// a string. This can also be useful if you have multiple tables
			// to ensure that the id is unique with a different prefix
			return 'row_'.$d;
		}
	),
	array(
			'db'        => 'pa_id',
			'dt'        => 0,
			'formatter' => function( $d, $row ) {
				global $modx;
				return $modx->runSnippet('pdoField', array('id' => $d, 'field' => 'pagetitle'));
			}
	),
	array(
			'db' => 'pc_start_date',
			'dt' => 1,
		'formatter' => function( $d, $row ) {
			return (!empty($d)) ? date( 'd.m.Y', strtotime($d)) : "";
		}
	),
	array(
		'db'        => 'pc_activations_count',
		'dt'        => 2,
	),
	array(
		'db' => 'bonus_sum',
		'dt' => 3,
		'formatter' => function( $d, $row ) {
			return (!empty($d)) ? $d . " руб." : "";
		}

	)
);


// SQL server connection information
$sql_details = array(
	'user' => 'mgmbee',
	'pass' => 'mB915009',
	'db'   => 'mgm',
	'host' => 'node100241-blogger.jelastic.regruhosting.ru'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require(MODX_CORE_PATH.'components/datatables/server_side/scripts/ssp_blg_pa.class.php' );

$beeWhere = array($_POST['beeWhere']);

echo json_encode(
	SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $beeWhere )
);