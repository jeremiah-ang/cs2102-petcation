<?php
include_once __DIR__ . "/required.php";

function has_submit_key ($post, $key) {
	return isset($post[$key]);
}

function has_update_key ($post) {
	return isset($post['key']);
}
function get_update_key ($post) {
	return explode(",", rawurldecode($post['key']));
}

function retrieve_row ($query_name, $params) {
	$cursor = execute_sql_params($query_name, $params);
	return pg_fetch_assoc($cursor);
}

function make_values ($key, $query_name) {
	$id = get_next_id ($query_name, get_username());
	$values = get_username(true);
	$values[$key] = $id;
	return $values;
}
function get_next_id ($query_name, $username=NULL) {
	$cursor = execute_sql_params($query_name, (is_null($username)) ? [] : [$username]);
	return pg_fetch_assoc($cursor)['next'];
}
function get_petids ($username) {
	$cursor = execute_sql_params("select pets of user", [$username]);
	if (is_null($cursor)) {
		return [];
	}
	$result = [];
	while ($row = pg_fetch_assoc($cursor)) {
		$result[] = [$row['petid'], $row['petname']];
	}
	return $result;
}
function get_walletlimit ($username) {
	$cursor = execute_sql_params("wallet limit", [$username]);
	return pg_fetch_assoc($cursor)['credits'];
}

function handle_submit ($post, $crud) {
	if (has_submit_key($post, $crud)) {
		$sql_query = parse_form($post, $crud);
		if (!$sql_query) {
			echo "Something Went Wrong!";
		} else {
			echo "Add Successful!";
		}
	} else {
		echo "No Submit Key! $crud <br/>";
		print_r($post);
	}
}

function retrieve_table ($query_name, 
						$params = [],
						$bid_fn = null,
						$update_fn="updateOnClick(this)", 
						$delete_fn="deleteOnClick(this)") {
	$cursor = execute_sql_params($query_name, $params);
	$keyed = false;
	$row_no = 1;
	echo "<table id='retrievedTable'>";
	while ($row = pg_fetch_assoc($cursor)) {
		if (!$keyed) {
			echo "<tr>";
			foreach ($row as $key => $value) {
				echo "<th>" . $key . "</th>";
			}
			echo (is_null($update_fn)) ? "" : "<th> Update </th>";
			echo (is_null($delete_fn)) ? "" : "<th> Delete </th>";
			echo (is_null($bid_fn)) ? "" : "<th> Bid </th>";

			echo "</tr>";
			$keyed = true;
		}

		echo "<tr>";
		foreach ($row as $key => $value) {
			echo "<td>" . $value . "</td>";
		}
		echo (is_null($update_fn)) ? "" : "<td><a href='pleaseEnableJavascript.html' onclick='$update_fn(".$row_no.");return false;'> Update </a></td>";
		echo (is_null($delete_fn)) ? "" : "<td><a href='pleaseEnableJavascript.html' onclick='$delete_fn(".$row_no.");return false;'> Delete </a></td>";
		echo (is_null($bid_fn)) ? "" : "<td><a href='pleaseEnableJavascript.html' onclick='$bid_fn(".$row_no.");return false;'> Bid </a></td>";
		echo "</tr>";
		$row_no++;
	}
	echo "</table>";
}

?>