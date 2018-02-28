<?php
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();
?>
<html>
	<head>
		<?php
			$page_info = print_retrieve_header (__FILE__);
		?>
	</head>
	<body>
		<h1> <?= gettitle($page_info['pageinfo']) ?> </h1>
		<?php
			retrieve_table("select " . gettablename($page_info['pageinfo']) . " by userid",
				get_username(true));
		?>
	</body>
</html>
