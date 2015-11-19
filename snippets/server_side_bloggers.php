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
$table = 'modx_bloggers';

// Table's primary key
$primaryKey = 'modUser_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
	array(
		'db' => 'modUser_id',
		'dt' => 'DT_RowId',
		'formatter' => function( $d, $row ) {
			// Technically a DOM id cannot start with an integer, so we prefix
			// a string. This can also be useful if you have multiple tables
			// to ensure that the id is unique with a different prefix
			return 'row_'.$d;
		}
	),
	array(
		'db' => 'modUserProfile_fullname',
		'dt' => 0
	),
	array(
		'db'        => 'modUserProfile_mobilephone',
		'dt'        => 1
	),
	array(
		'db'        => 'modUser_username',
		'dt'        => 2
	),
	array(
			'db' => 'modUserProfile_extended',
			'dt' => 3
	)

);

// SQL server connection information
$sql_details = array(
	'user' => 'mgmbee',
	'pass' => 'mB915009',
	'db'   => 'mgmbee',
	'host' => 'node91560-mgmbee.jelastic.regruhosting.ru'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require(MODX_CORE_PATH.'components/datatables/server_side/scripts/ssp.class.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);