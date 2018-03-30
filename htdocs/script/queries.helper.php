<?php
include_once __DIR__ . "/../db/forms.php";
include_once __DIR__ ."/../db/queries.php";

function generate_select_statements (&$queries) {
	global $PRIMARY_KEYS;
	foreach ($PRIMARY_KEYS as $key=>$value) {
		$selectall = "select * $key";
		$selectallquery = "select * from $key";

		$queries[$selectall] = $selectallquery;
	}
}

function generate_keys_statements (&$queries) {
	global $PRIMARY_KEYS;

	foreach ($PRIMARY_KEYS as $key=>$value) {
		$key_placeholder = generate_placeholder ($value);

		$selectallkey = "select * from $key with key";
		$deleteallkey = "delete from $key";
		$selectallkeyquery = "select * from $key where $key_placeholder";
		$deleteallkeyquery = "delete from $key where $key_placeholder";

		$queries[$selectallkey] = $selectallkeyquery;
		$queries[$deleteallkey] = $deleteallkeyquery;
	}
}
function generate_placeholder ($value) {
	$placeholders = "";
	$andflag = false;
	for($i = 0; $i < count($value); $i++) {
		if (!$andflag) {
			$andflag = true;
		} else {
			$placeholders .= " AND ";
		}
		$placeholders .= $value[$i] . ' = $' . ($i + 1);
	}
	return $placeholders;
}

function generate_select_by_user_statements ($tables, &$queries) {
	foreach ($tables as $table=>$col) {
		$key = "select $table by userid";
		$value = "select * from $table where status = 'ACTIVE' AND userid = $1";
		$queries[$key] = $value;
	}
}

?>