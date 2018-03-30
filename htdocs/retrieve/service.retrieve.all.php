<?php
include_once __DIR__ . "/../script/retrieve.header.php";
$username = "";
if (has_username()) {
	$username = get_username();
}

?>
<html>
	<head>
		<?php
			$page_info = print_retrieve_header (__FILE__);

			$custom_link = [];

			if (has_username()) {
				$linkname = 'Bid';
				$fn_name = "bidOnClick";
				make_retrieved_onClick($page_info, $fn_name, "/create/bids.create.php");
				$custom_link = [make_custom_col_link ($linkname, $fn_name)];
			} else {
				$linkname = 'View';
				$fn_name = "bidOnClick";
				make_retrieved_onClick($page_info, $fn_name, "/retrieve/service.retrieve.php");
				$custom_link = [make_custom_col_link ($linkname, $fn_name)];
			}

			

		?>
	</head>
	<body>
		<h1> <?= gettitle($page_info['pageinfo'])  ?> </h1>
		<?php
			retrieve_table("select all unwon services",
							[$username], 
							$custom_link, NULL, NULL);
		?>
	</body>
</html>
