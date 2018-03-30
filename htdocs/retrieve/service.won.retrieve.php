<?php
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();
?>
<html>
	<head>
		<?php
			$page_info = print_retrieve_header (__FILE__);
			$fn_name = "bidOnCLick";
			make_retrieved_onClick ($page_info, $fn_name, "/retrieve/bids.retrieve.php");
		?>
	</head>
	<body>
		<h1> <?= gettitle($page_info['pageinfo'])  ?> </h1>
		<?php
			retrieve_table("select service won by userid", 
				[get_username()],
				[], NULL, NULL);
		?>
	</body>
</html>
