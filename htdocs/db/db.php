<?php
include_once __DIR__ . '/db_info.php';
include_once __DIR__ . '/../script/queries.helper.php';
include_once __DIR__ . '/insertupdate.php';

$DATABASE = NULL;
function DB() {
	global $DATABASE, $HOST, $PORT, $DBNAME, $USER, $PASSWORD;
	if ($DATABASE != NULL)
		return $DATABASE;

	 $DATABASE = pg_connect("host=".$HOST." port=".$PORT." dbname=".$DBNAME." user=".$USER." password=".$PASSWORD) or die (pg_last_error());;
	 return $DATABASE;
}

function execute_sql_params ($query_name, $params=[], $is_raw=FALSE) {
	try {
		return execute_sqls_params ([$query_name], [$params], $is_raw)[0];
	} catch (Exception $e) {
		throw $e;
	}
}

function execute_sqls_params ($queries, $params, $is_raw=FALSE) {
	$db = DB();
	$results = [];
	start_transaction ($db);
	for ($i = 0; $i < count($queries); $i++) {
		try {
			$results[] = execute($db, $queries[$i], $params[$i], $is_raw);
		} catch (Exception $e) {
			rollback_transaction ($db);
			throw $e;
		}
	}
	end_transaction ($db);
	return $results;
}

function start_transaction ($db) {
	pg_query ($db, "begin") or die (pg_last_error());
}

function end_transaction ($db) {
	pg_query ($db, "commit") or die (pg_last_error());
}

function rollback_transaction ($db) {
	pg_query ($db, "rollback") or die (pg_last_error());
}

function get_query($query_name) {
	global $QUERIES;
	return $QUERIES[$query_name];
}

function execute ($db, $query, $params=[], $is_raw=FALSE) {
	global $QUERIES;

	$sql = ($is_raw) ? $query : $QUERIES[$query];
	pg_send_query_params($db, $sql, $params);
	pg_set_error_verbosity($db, PGSQL_ERRORS_VERBOSE);
	$result = pg_get_result($db);
	$error = pg_result_error($result);
	if($error != "") {
		throw new Exception(pg_result_error_field($result, PGSQL_DIAG_MESSAGE_PRIMARY));
		return NULL;
	} else {
		return $result;
	}
}

?>