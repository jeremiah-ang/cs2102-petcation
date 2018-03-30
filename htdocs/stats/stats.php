<?php
include_once __DIR__ . "/../script/retrieve.header.php";
?>

<html>
	<head>
		<title> Stats </title>
		<?php
			$page_info = print_retrieve_header ("stats.retrieve.php");
		?>
	</head>
	<body>

		<h1> Stats </h1>

		<h2> Avg price of Pet service </h2>
		<?php
			retrieve_table("buyer stats", [], [], null, null);
		?>
		<h2> Most Popular Pet Type Owned </h2>
		<?php
			retrieve_table("ranked pet type owned", [], [], null, null);
		?>
		<h2> Most Popular Pet Type Served </h2>
		<?php
			retrieve_table("ranked pet type served", [], [], null, null);
		?>
	</body>
</html>