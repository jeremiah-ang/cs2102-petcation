<?php
include_once "../db/db.php";
?>
<html>
	<head>
		<title> Create Pet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Pet </h1>
		<form name="createPet" method="post" onsubmit="return createPetOnSubmit(this);"> 
			<ul>
				<li><label>Pet Id: </label><input type="text" name="petId"/></li>
				<li><label>Owner username: </label><input type="text" name="userId"/><br/></li>
				<li><label>name: </label><input type="text" name="name"/><br/></li>
				<li><label>size: </label><input type="text" name="size"/><br/></li>
				<li><label>picture: </label><input type="text" name="picture"/><br/></li>
				<li><input type='submit'></input></li>
			</ul>
		</form>
		<script>
			var createPetOnSubmit = createOnSubmit("Pet");
		</script>
	</body>
</html>