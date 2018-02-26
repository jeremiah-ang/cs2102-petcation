<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Bids </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Retrieve Bids </h1>
		<?php
			retrieve_table("select * bids");
		?>
	</body>
</html>