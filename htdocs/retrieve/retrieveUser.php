<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Retrieve User </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
		<script type="text/javascript">
			updateOnClick = makeRetrievedOnClick("/petcation/update/updateUser.php", ['userid', 'username']);
			deleteOnClick = makeRetrievedOnClick("/petcation/update/updateUser.php", ['userid', 'username']);
		</script>
	</head>
	<body>
		<h1> Retrieve User </h1>
		<?php
			retrieve_table("select * users");
		?>
	</body>
</html>
