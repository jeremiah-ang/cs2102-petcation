<?php
include_once __DIR__ . "/../script/required.php";
$pageinfo = extract_page_info (__FILE__);
$crud = $pageinfo[1];
$tablename = $pageinfo[0];
$title = $pageinfo[2];

$params = array_map(
		function ($x) { return rawurldecode($x); }, 
		explode(",", $_POST['key'])
	);
execute_sql_params("insert into winners", $params);
?>

