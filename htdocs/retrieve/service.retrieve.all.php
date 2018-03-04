<?php
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();
?>
<html>
	<head>
		<?php
			$page_info = print_retrieve_header (__FILE__);
			$fn_name = "bidOnClick";
			make_retrieved_onClick($page_info, $fn_name, "/create/bids.create.php");
		?>
	</head>
	<body>
		<h1> <?= gettitle($page_info['pageinfo'])  ?> </h1>
		<?php
			retrieve_table("select all unwon services", // "select * " . gettablename($page_info['pageinfo']), 
							[], 
							[make_custom_col_link ("Bid", $fn_name)]);
		?>
	</body>
</html>
