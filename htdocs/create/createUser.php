<?php
include_once "../db/db.php";
?>
<html>
	<head>
		<title> Create User </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create User </h1>
		<form name="createUser" method="post" onsubmit="return true;"> 
			<ul>
				<li><label>Username: </label><input type="text" name="userId"/></li>
				<li><label>Full Name: </label><input type="text" name="name"/><br/></li>
				<li><label>Date Of birth: </label><input type="text" name="dateOfBirth"/><br/></li>
				<li><label>Address: </label><textarea type="text" name="address"/></textarea><br/></li>
				<li><input type='submit' name="submit"></input></li>
			</ul>
		</form>
		<?php
			if (isset($_POST['submit'])) {
				parse_form($_POST);
			}
		?>
		<script>
			var createUserOnSubmit = createOnSubmit("User");
			console.log(createUserOnSubmit)
		</script>
	</body>
</html>
