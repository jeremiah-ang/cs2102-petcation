<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create Pet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Pet </h1>
		<form name="createPet" method="post" onsubmit="return createPetOnSubmit(this);"> 
			<input type='hidden' name='table' value='Pets'></input>
			<ul>
				<li><label>Pet Id: </label><input type="text" name="petId"/></li>
				<li><label>Owner username: </label><input type="text" name="userId"/><br/></li>
				<li><label>name: </label><input type="text" name="petname"/><br/></li>
				<li><label>size: </label><input type="text" name="sizeofpet"/><br/></li>
				<li><label>picture: </label><input type="text" name="picture"/><br/></li>
				<li><input type='submit' name="submit"></input></li>
			</ul>
		</form>
		<?php
			if (has_submit_key($_POST)) {
				$result = parse_form($_POST);
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