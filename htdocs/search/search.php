<?php
include_once __DIR__ . "/../script/required.php";
$username = "";
if (has_username()) {
	$username = get_username();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Search Result</title>
	<script src = "../script/helpers.js" type="text/javascript"></script>
	<?php
			$page_info = ["pk" => '["serviceid","userid"]'];
			$custom_link = [];

			if (has_username()) {
				$linkname = 'Bid';
				$fn_name = "bidOnClick";
				make_retrieved_onClick($page_info, $fn_name, "/create/bids.create.php");
				$custom_link = [make_custom_col_link ($linkname, $fn_name)];
			} else {
				$linkname = 'View';
				$fn_name = "bidOnClick";
				make_retrieved_onClick($page_info, $fn_name, "/retrieve/service.retrieve.php");
				$custom_link = [make_custom_col_link ($linkname, $fn_name)];
			}
	?>
</head>
<body>
	<form method="post">
		<ul>
			<li><label> From Start Date: </label><input type = 'date' name = 'startdate' /></li>
			<li><label> To End Date: </label><input type = 'date' name = 'enddate' /></li>
			<li><label> Pet Type: </label>
				<select name ='pettype'>
					<option value='2'>Cat</option>
					<option value='1'>Dog</option>
					<option value='3'>Bird</option>
					<option value='4'>Rabbit</option>
					<option value='5'>Hamster</option>
				</select>
			</li>
			<li><input type = 'submit' name='Search'/></li>
		</ul>
	</form>

	<?php
	if (isset($_POST['Search'])) {
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$pettype = $_POST['pettype'];
		echo "<h1> Search Result </h1>";
		retrieve_table("search services",
							[$username, $pettype, $startdate, $enddate], 
							$custom_link, NULL, NULL);
	}
	?>

	</form>
</body>
</html>