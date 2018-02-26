<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create Service </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Service </h1>
		<form name="createService" method="post" onsubmit="return createServiceOnSubmit(this);">  
			<input type='hidden' name='table' value='Service'></input>
			<ul>
				<li><label>Service Id: </label><input type="text" name="serviceId"/></li>

				<li><label>Seler username: </label><input type="text" name="sellerId"/><br/></li>

				<li><label>Start Date: </label><input type="text" name="startDate"/><br/></li>

				<li><label>End Date: </label><textarea type="text" name="endDate"/></textarea><br/></li>

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
			var createServiceOnSubmit = createOnSubmit("Service");
		</script>
	</body>
</html>