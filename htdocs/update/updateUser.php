<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Update User </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Update User </h1>

		<?php 
			print_r($_POST);
			if (has_update_key($_POST)) {
				$key = get_update_key($_POST);
				$row = retrieve_row ("select * from user with key", $key);
				print_r($row);
		?>
				<form name="createUser" method="post" onsubmit="return createUserOnSubmit(this);"> 
					<input type='hidden' name='table' value='Users'></input>
					<input type='hidden' name='key1' value='userId'></input>
					<input type='hidden' name='key2' value='username'></input>
					<ul>
						<li><label>Username: </label><input type="text" name="userId" value="<?= $row['userid'] ?>" readonly="readonly"/></li>
						<li><label>Full Name: </label><input type="text" name="username" value="<?= $row['username'] ?>" readonly="readonly"/><br/></li>
						<li><label>Date Of birth: </label><input type="text" name="dateOfBirth" value="<?= $row['dateofbirth'] ?>"/><br/></li>
						<li><label>Address: </label><textarea type="text" name="address"/><?= $row['address'] ?></textarea><br/></li>
						<li><input type='submit' name="submit"></input></li>
					</ul>
				</form>

		<?php
			}
		?>

		
		<?php
			if (has_submit_key($_POST)) {
				$sql_query = parse_form($_POST, true);
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