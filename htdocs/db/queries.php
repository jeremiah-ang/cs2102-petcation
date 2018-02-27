<?php
include_once __DIR__ . "/forms.php";
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
function generate_next_id_statements ($tables, &$queries) {

	foreach ($tables as $table=>$col) {
		$key = "$table next id";
		$value = "select case count($col) when 0 then 1 else max($col) + 1 end as next from $table where userid = $1";
		$queries[$key] = $value;
	}

}

function generate_select_by_user_statements ($tables, &$queries) {
	foreach ($tables as $table=>$col) {
		$key = "select $table by userid";
		$value = "select * from $table where userid = $1";
		$queries[$key] = $value;
	}
}

$QUERIES = [
	"is valid user" => "select * from users where userid = $1",
	"select 1 user" => "select * from users where userid = $1",
	"bid next id" => "select case count(bidid) when 0 then 1 else max(bidid) + 1 end as next from bids",
	"select pets of user" => "select petid, petName from pets where userid = $1",
	"bid bought" => "select * from bids where buyerid = $1 order by petid",
	"wallet limit" => "select credits from users where userid = $1"
];

generate_select_statements($QUERIES);
generate_keys_statements($QUERIES);

$tables = [
		"pets" => "petid",
		"wallet" => "topupid",
		"service" => "serviceid"
	];
generate_next_id_statements($tables, $QUERIES);
generate_select_by_user_statements($tables, $QUERIES);
?>