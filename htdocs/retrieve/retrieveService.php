<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Service </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Retrieve Service </h1>
		<?php
			retrieve_table("select * service");
		?>
	</body>
</html>