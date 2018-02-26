<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Transaction </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Retrieve Transaction </h1>
		<?php
			retrieve_table("select * trans");
		?>
	</body>
</html>