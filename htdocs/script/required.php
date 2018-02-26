<?php
session_start();
require_once __DIR__ . "/../db/db.php";

function has_submit_key ($post) {
	return isset($post['submit']);
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

function retrieve_table ($query_name, 
						$update_fn="updateOnClick(this)", 
						$delete_fn="deleteOnClick(this)") {
	$cursor = execute_sql_params($query_name);
	$keyed = false;
	$row_no = 1;
	echo "<table id='retrievedTable'>";
	while ($row = pg_fetch_assoc($cursor)) {
		if (!$keyed) {
			echo "<tr>";
			foreach ($row as $key => $value) {
				echo "<th>" . $key . "</th>";
			}
			echo "<th> Update </th><th> Delete </th>";
			echo "</tr>";
			$keyed = true;
		}

		echo "<tr>";
		foreach ($row as $key => $value) {
			echo "<td>" . $value . "</td>";
		}
		echo "<td><a href='pleaseEnableJavascript.html' onclick='$update_fn(".$row_no.");return false;'> Update </a></td>";
		echo "<td><a href='pleaseEnableJavascript.html' onclick='$delete_fn(".$row_no.");return false;'> Delete </a></td>";
		echo "</tr>";
		$row_no++;
	}
	echo "</table>";
}

?>