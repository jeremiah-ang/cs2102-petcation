<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Update Pet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Update Pet </h1>
		<?php 
			print_r($_POST);
			if (has_update_key($_POST)) {
				$key = get_update_key($_POST);
				$row = retrieve_row ("select * from pets with key", $key);
				print_r($row);
		?>

				<form name="createPet" method="post" onsubmit="return createPetOnSubmit(this);"> 
					<input type='hidden' name='table' value='Pets'></input>
					<input type='hidden' name='key1' value='petId'></input>
					<input type='hidden' name='key2' value='userId'></input>
					<ul>
						<li><label>Pet Id: </label><input type="text" name="petId" value="<?= $row['petid'] ?>" readonly="readonly"/></li>
						<li><label>Owner username: </label><input type="text" name="userId" value="<?= $row['userid'] ?>" readonly="readonly"/><br/></li>
						<li><label>name: </label><input type="text" name="petname" value="<?= $row['petname'] ?>"/><br/></li>
						<li><label>size: </label><input type="text" name="sizeofpet" value="<?= $row['sizeofpet'] ?>"/><br/></li>
						<li><label>picture: </label><input type="text" name="picture" value="<?= $row['picture'] ?>"/><br/></li>
						<li><input type='submit' name="submit"></input></li>
					</ul>
				</form>

		<?php
			}
		?>

		<?php
			if (has_submit_key($_POST)) {
				$result = parse_form($_POST, true);
				if (!$result) {
					echo "Something Went Wrong!";
				} else {
					echo "Pet successfully added!";
				}
			}
		?>
		<script>
			var createPetOnSubmit = createOnSubmit("Pet");
		</script>
	</body>
</html>