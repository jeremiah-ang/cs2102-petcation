<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create Bids </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Bids </h1>
		<form name="createBids" method="post" onsubmit="return createBidsOnSubmit(this);"> 
			<input type='hidden' name='table' value='Bids'></input>
			<ul>
				<li><label>Username: </label><input type="text" name="userId"/></li>
				<li><label>Full Name: </label><input type="text" name="name"/><br/></li>
				<li><label>Date Of birth: </label><input type="text" name="dateOfBirth"/><br/></li>
				<li><label>Address: </label><textarea type="text" name="address"/></textarea><br/></li>
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
			var createBidsOnSubmit = createOnSubmit("Bids");
		</script>
	</body>
</html>