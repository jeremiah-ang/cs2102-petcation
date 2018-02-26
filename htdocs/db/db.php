<?php
include_once __DIR__ . '/db_info.php';
include_once __DIR__ . '/queries.php';
include_once __DIR__ . '/insertupdate.php';

$DATABASE = NULL;
function DB() {
	global $DATABASE, $HOST, $PORT, $DBNAME, $USER, $PASSWORD;
	if ($DATABASE != NULL)
		return $DATABASE;

	 $DATABASE = pg_connect("host=".$HOST." port=".$PORT." dbname=".$DBNAME." user=".$USER." password=".$PASSWORD);
	 return $DATABASE;
}

function execute_sql_params ($query_name, $params=[]) {
	global $QUERIES;
	try {
		$result = pg_query_params(DB(), $QUERIES[$query_name], $params);
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