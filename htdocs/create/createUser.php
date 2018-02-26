<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create User </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create User </h1>
		<form name="createUser" method="post" onsubmit="return createUserOnSubmit(this);"> 
			<input type='hidden' name='table' value='Users'></input>
			<ul>
				<li><label>Username: </label><input type="text" name="userId"/></li>
				<li><label>Full Name: </label><input type="text" name="username"/><br/></li>
				<li><label>Date Of birth: </label><input type="text" name="dateOfBirth"/><br/></li>
				<li><label>Address: </label><textarea type="text" name="address"/></textarea><br/></li>
				<li><input type='submit' name="submit"></input></li>
			</ul>
		</form>
		<?php
			if (has_submit_key($_POST)) {
				$sql_query = parse_form($_POST);
				if (!$sql_query) {
					echo "Something Went Wrong!";
				} else {
					echo "Add Successful!";
				}
			}
		?>
		<script>
			var createUserOnSubmit = createOnSubmit("User");
			console.log(createUserOnSubmit)
		</script>
	</body>
</html>
