<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Winner </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Retrieve Winner </h1>
		<?php
			retrieve_table("select * winners");
		?>
	</body>
</html>