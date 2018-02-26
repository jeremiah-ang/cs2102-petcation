<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve Pet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
		<script type="text/javascript">
			updateOnClick = makeRetrievedOnClick("/petcation/update/updatePets.php", ['petid', 'userid']);
			deleteOnClick = makeRetrievedOnClick("/petcation/update/updatePets.php", ['petid', 'userid']);
		</script>
	</head>
	<body>
		<h1> Retrieve Pet </h1>
		<?php
			retrieve_table("select * pets");
		?>
	</body>
</html>