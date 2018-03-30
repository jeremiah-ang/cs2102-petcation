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
	return -1;
}

function get_service ($sellerid, $serviceid) {
	$cursor = execute_sql_params("get service", [$sellerid, $serviceid]);
	if (is_null($cursor)) { return NULL; }
	if (pg_num_rows($cursor) == 0) { return NULL; }
	$result = pg_fetch_assoc($cursor);
	return $result;
}
function get_petids ($username, $pettype=NULL) {
	if (is_null($pettype)) {
		$cursor = execute_sql_params("get all petids", [$username]);
	} else {
		$cursor = execute_sql_params("get petids for bidding", [$username, $pettype]);
	}
	if (is_null($cursor)) {
		return [];
	}
	$result = [];
	while ($row = pg_fetch_assoc($cursor)) {
		$result[] = [$row['petid'], $row['petname']];
	}
	return $result;
}
function get_pettypes () {
	$results = execute_sql_params("get pet types", []);
	$pettypes = [];
	while ($row = pg_fetch_assoc($results)) {
		$pettypes[] = [$row['typeid'], $row['typedescription']];
	}
	return $pettypes;
}
function get_walletlimit ($username) {
	$cursor = execute_sql_params("wallet limit", [$username]);
	return pg_fetch_assoc($cursor)['credits'];
}

function handle_submit (
	$post, 
	$crud, 
	$sql=NULL) {
	if (!is_null($sql)) {
		$sql = get_query($sql);
	}
	
	if (has_submit_key($post, $crud)) {
		$sql_query = parse_form($post, $crud, $sql);
		return !is_null($sql_query);
	}
	return false;
}

function handle_winner_create ($sellerid, $serviceid, $winners) {
	$queries = [];
	$params = [];
	
	foreach ($winners as $winner) {
		$buyerid = $winner[0];
		$petid = $winner[1];
		$params[] = [$sellerid, $serviceid, $buyerid, $petid];
		$queries[] = "create winner";
	}

	$queries[] = "refund";
	$params[] = [$sellerid, $serviceid];

	$queries[] = "delete from service";
	$params[] = [$serviceid, $sellerid];

	execute_sqls_params($queries, $params);
}

function make_retrieved_onClick ($page_info, $fn_name, $link, $params=[]) {
	echo "<script> 
			$fn_name = makeRetrievedOnClick('/petcation"
			. $link 
			."', "
			. $page_info['pk'] 
			.", " 
			. json_encode($params) 
			. ");
		</script>";
}
function make_custom_col_link ($colheader, $fn_name) {
	return [
		"header" => $colheader,
		"value" => function ($row_no) use (&$colheader, &$fn_name) {
			return "<a href='pleaseEnableJavascript.html' onclick='$fn_name(this)(".$row_no.");return false;'> $colheader </a>";
		}
	];
}
function retrieve_table ($query_name, 
						$params = [],
						$custom=[],
						$update_fn="updateOnClick(this)", 
						$delete_fn="deleteOnClick(this)") {
	$cursor = execute_sql_params($query_name, $params);
	$keyed = false;
	$row_no = 1;
	echo "<table id='retrievedTable'>";
	if (pg_num_rows($cursor) == 0) {
		echo "<h2> 0 rows </h2>";
		return;
	}
	while ($row = pg_fetch_assoc($cursor)) {
		if (!$keyed) {
			echo "<tr>";
			foreach ($row as $key => $value) {
				echo "<th>" . $key . "</th>";
			}
			echo (is_null($update_fn)) ? "" : "<th> Update </th>";
			echo (is_null($delete_fn)) ? "" : "<th> Delete </th>";
			if (count($custom) > 0) {
				foreach ($custom as $col) {
					echo "<th> " . $col['header'] . "</th>";
				}
			}
			


			echo "</tr>";
			$keyed = true;
		}

		echo "<tr>";
		foreach ($row as $key => $value) {
			echo "<td>" . $value . "</td>";
		}
		echo (is_null($update_fn)) ? "" : "<td><a href='pleaseEnableJavascript.html' onclick='$update_fn(".$row_no.");return false;'> Update </a></td>";
		echo (is_null($delete_fn)) ? "" : "<td><a href='pleaseEnableJavascript.html' onclick='$delete_fn(".$row_no.");return false;'> Delete </a></td>";

		if (count($custom) > 0) {
			foreach ($custom as $col) {
				echo "<td>" . $col['value']($row_no) . "<td>";
			}
		}
		

		echo "</tr>";
		$row_no++;
	}
	echo "</table>";
}

?>