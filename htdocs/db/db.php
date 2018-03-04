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

function execute_sql_params ($query_name, $params=[]) {
	return execute_sqls_params ([$query_name], [$params])[0];
}

function execute_sqls_params ($queries, $params) {
	$db = DB();
	$results = [];
	start_transaction ($db);
	for ($i = 0; $i < count($queries); $i++) {
		$results[] = execute($db, $queries[$i], $params[$i]);
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

function execute ($db, $query, $params=[]) {
	global $QUERIES;
	print_r ($params);
	try {
		$result = pg_query_params($db, $QUERIES[$query], $params) or die (pg_last_error());;
		if (!$result) {
			echo "Something Went Wrong!";
			return NULL;
		} else {
			return $result;
		}
	} catch (Exception $e) {
		echo e;
	}
}

?>