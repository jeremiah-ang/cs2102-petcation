<?php
include_once __DIR__ . '/../script/required.php';

$page_info = extract_page_info (__FILE__);
if (isset($_POST['key'])) {
	$query = "delete from " . gettablename($page_info);

	$keys = explode(",", $_POST['key']);
	$fn = function (&$s) { rawurldecode($s); };
	array_map($fn, $keys);

	try {
		$result = execute_sql_params ($query, $keys);
		if (!$result) {
			echo "Failed to delete!";
		} else {
			echo gettablename($page_info) . ": " . implode(", ", $keys) . " Deleted! <br/>";
			echo "Redirecting ...";
			redirect5s('/retrieve/'.gettablename($page_info).'.retrieve'.getextra($page_info).'.php');
		}
	} catch (Exception $e) {
		echo "<div class = 'error'>". $e->getMessage() ."</div>";
	}
}
?>