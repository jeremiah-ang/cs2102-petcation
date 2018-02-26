<?php
require_once __DIR__ . "/db.php";

# Convert $_POST to $table, $keys and $values in an array
function tablekeyvalue ($post) {
	$keys = [];
	$values = [];
	$table = NULL;

	# Iterate through $_POST
	# keys should only have value as 'name' in form
	foreach($post as $key => $value) {
		if ($key === "submit") continue;
		if ($key === "table") {
			$table = $value;
			continue;
		}  
		$keys[] = $key;
		$values[] = $value;
	}

	# Return a tuple of the 3 elements
	return ["table" => $table, 
			"keys" => $keys, 
			"values" => $values];
}

function make_insert_sql_params ($table, $keys, $placeholders, $values=NULL) {

	if (is_null($table)) {
		return NULL;
	}

	$keys = implode(",", $keys);
	$placeholders = implode(",", $placeholders);

	$sql = "INSERT INTO " . $table . " (" . $keys . ") VALUES (";
	if (!is_null($values)) {
		$wrapper = function ($x) { return "'" . $x . "'"; };
		$values = array_map($wrapper, $values);
		echo  $sql . implode(",", $values) . ")";
	}

	$sql .= $placeholders . ")";
	return $sql;
}

function prep_update_placeholder (&$sql, $arr, $seperator, $offset=0) {
	$front_comma = false;
	for ($i = 0; $i < count($arr); $i++) {
		if ($front_comma == false) {
			$front_comma = true;
		} else {
			$sql .= $seperator;
		}
		$sql .= $arr[$i] . " = $" . ($i + 1 + $offset);
	}
}
function make_update_sql_params ($table, $post, &$values) {

	if (is_null($table)) {
		return NULL;
	}

	$sql = $sql2 = "UPDATE " . $table . " SET ";
	$selector_key = [];
	$selector_value = [];
	$tmp = [];
	foreach ($post as $key => $value) {
		if (substr($key, 0, 3) === "key") {
			echo $value . "<br>";
			$selector_key[] = $value;
			$selector_value[] = $post[$value];
		} else if ($key !== "table" && $key !== "submit") {
			$tmp[$key] = $value;
		}
	}

	$keys = [];
	$values = [];
	foreach ($tmp as $key => $value) {
		if (!in_array($key, $selector_key)) {
			$keys[] = $key;
			$values[] = $value;
		}
	}

	print_r($values);
	prep_update_placeholder ($sql, $keys, ", ", $offset=0);
	$sql .= " WHERE ";
	prep_update_placeholder ($sql, $selector_key, " AND ", $offset=count($values));
	

	foreach ($selector_value as $key => $value) {
		$values[] = $value;
	}

	return $sql;
}

function make_placeholders ($values) {
	$placeholders = [];
	for ($i = 0; $i < count($values); $i++) {
		$placeholders[] = "$" . ($i + 1);
	}
	return $placeholders;
}

function parse_insert_form ($post) {
	$tablekeyvalue = tablekeyvalue($post);
	$placeholders = make_placeholders ($tablekeyvalue['values']);
	$sql = make_insert_sql_params ($tablekeyvalue['table'], 
							$tablekeyvalue["keys"], 
							$placeholders, 
							$tablekeyvalue["values"]);
	print_r($sql);
	$db = DB();
	$result = pg_query_params($db, $sql, $tablekeyvalue["values"]);
	return $result;
}

function parse_update_form ($post) {
	$tablekeyvalue = tablekeyvalue($post);
	$sql = make_update_sql_params ($tablekeyvalue['table'], 
							$post,
							$tablekeyvalue["values"]);
	print_r($sql);
	$db = DB();
	$result = pg_query_params($db, $sql, $tablekeyvalue["values"]);
	return $result;
}

function parse_form ($post, $update=false) {
	if ($update) {
		return parse_update_form($post);
	} else {
		return parse_insert_form($post);
	}
}
?>