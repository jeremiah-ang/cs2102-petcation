<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Wallet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Retrieve Wallet </h1>
		<?php
			retrieve_table("select * wallet");
		?>
	</body>
</html>