<?php
include ('db_info.php');
$DATABASE = NULL;
function DB() {
	global $DATABASE, $HOST, $PORT, $DBNAME, $USER, $PASSWORD;
	if ($DATABASE != NULL)
		return $DATABASE;

	 $DATABASE = pg_connect("host=".$HOST." port=".$PORT." dbname=".$DBNAME." user=".$USER." password=".$PASSWORD);
	 return $DATABASE;
}

function parse_form ($post) {
	print_r($post);
	$keys = [];
	$values = [];
	foreach($post as $key => $value) {
		if ($key === "submit") continue;
		$keys[] = $key;
		$values[] = $value;
	}


	$keys = implode(",", $keys);
	$values = implode(",", $values);
	$table = "Users";
	$sql = "INSERT INTO " . $table . " (" . $keys . ") VALUES (" . $values . ")";
	echo $sql;
	// var input = {};
	// for (var i = 0; i < elements.length; i++) {
	// 	input[elements[i].name] = elements[i].value;
	// }
	// delete input[''];
	// keys = Object.keys(input).join(",")
	// values = Object.values(input).join(",")
	// var sql = "INSERT INTO " + table + " (" + keys + ") VALUES (" + values + ")";

	// return sql
}
?>