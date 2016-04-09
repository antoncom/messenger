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
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
	array(
		'db' => 'id',
		'dt' => 'DT_RowId',
		'formatter' => function( $d, $row ) {
			// Technically a DOM id cannot start with an integer, so we prefix
			// a string. This can also be useful if you have multiple tables
			// to ensure that the id is unique with a different prefix
			return 'row_'.$d;
		}
	),
	array(
		'db' => 'pagetitle',
		'dt' => 0,
		'formatter' => function( $d, $row ) {
			return '<a href="' . $d . '.html">' . $d . '</a>';
		}
	),
	array(
		'db'        => 'pc_start_date',
		'dt'        => 1,
		'formatter' => function( $d, $row ) {
			return (!empty($d)) ? date( 'd.m.Y', strtotime($d)) : "";
		}
	),
	array(
		'db'        => 'pc_end_date',
		'dt'        => 2,
		'formatter' => function( $d, $row ) {
			return (!empty($d)) ? date( 'd.m.Y', strtotime($d)) : "";
		}
	),
/*	array(  'db' => 'pa_id',
			'dt' => 3,
			'formatter' => function( $d, $row ) {
				global $modx;
				return str_pad($modx->runSnippet('pdoField', array('id' => $d, 'field' => 'pa_code')), 2, '0', STR_PAD_LEFT);
			}
	),*/
	array( 'db' => 'pc_activations_count', 'dt' => 3 )
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

require(MODX_CORE_PATH.'components/datatables/server_side/scripts/ssp.class.php' );

// Фильтрация POST-данных - согласно требованию Билайн (см. таблицу требования - blog-04)
require(MODX_BASE_PATH.'snippets/utils/remove_uncorrect_symbols_from_POST.php' );


// здесь принимаем pa_id
$beeWhere = (!empty($_POST['beeWhere']))
	? array($_POST['beeWhere'])
	: array();

// здесь принимаем blogger_id and pc_start_date
$beeWhereConstant = explode(' AND ', $_POST['beeWhereConstant']);
if(count($beeWhereConstant) > 0) $beeWhere = array_merge($beeWhere, $beeWhereConstant);

//$modx->log(xPDO::LOG_LEVEL_ERROR, "REQUEST = " . print_r($_REQUEST, true));

echo json_encode(
	SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $beeWhere )
);