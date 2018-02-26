<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create Winner </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Winner </h1>
		<form name="createWinner" method="post" onsubmit="return createWinnersOnSubmit(this);">  
			<input type='hidden' name='table' value='Winner'></input>
			<ul>
				<li><label>Buyer Id: </label><input type="text" name="userId"/></li>

				<li><label>Pet Id: </label><input type="text" name="name"/><br/></li>

				<li><label>Seller Id: </label><input type="text" name="dateOfBirth"/><br/></li>

				<li><label>Service Id: </label><textarea type="text" name="address"/></textarea><br/></li>

				<li><label>Amount: </label><textarea type="text" name="address"/></textarea><br/></li>

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
			var createWinnersOnSubmit = createOnSubmit("Winners");
		</script>
	</body>
</html>