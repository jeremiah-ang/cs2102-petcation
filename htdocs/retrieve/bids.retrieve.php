<?php
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();

if (!isset($_POST['key'])) {
	die("Error! No key found!");
}

$params = array_map(
	function ($x) { return rawurldecode($x); }, 
	explode(",", $_POST['key'])
);
?>
<html>
	<head>
		<?php
			$page_info = print_retrieve_header (__FILE__);
			$fn_name = "acceptBids";

			pageinfo_setpk ($page_info, ['buyerid', 'petid', 'amt']);
			make_retrieved_onClick($page_info, $fn_name, "/create/winners.create.php", explode(",", $_POST['key']));
		?>
	</head>
	<body>
		<h1> <?= gettitle($page_info['pageinfo']) ?> </h1>
		<?php
			retrieve_table("select " . gettablename($page_info['pageinfo']) . " by serviceid and userid",
				$params, 
				[make_custom_col_link ("Accept", $fn_name)]);
		?>
	</body>
</html>
