<?php
include_once "../db/db.php";
?>
<html>
	<head>
		<title> Create Bids </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create User </h1>
		<form name="createBids" method="post" onsubmit="return createBidsOnSubmit(this);"> 
			<ul>
				<li><label>Username: </label><input type="text" name="userId"/></li>
				<li><label>Full Name: </label><input type="text" name="name"/><br/></li>
				<li><label>Date Of birth: </label><input type="text" name="dateOfBirth"/><br/></li>
				<li><label>Address: </label><textarea type="text" name="address"/></textarea><br/></li>
				<li><input type='submit'></input></li>
			</ul>
		</form>
		<script>
			var createBidsOnSubmit = createOnSubmit("Bids");
		</script>
	</body>
</html>