<?php
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();


if (array_key_exists('Submit', $_POST)) {
	$serviceid = $_POST['serviceid'];
	$sellerid = $_POST['sellerid'];
	unset($_POST["Submit"]);
	unset($_POST["serviceid"]);
	unset($_POST["sellerid"]);
	$winners = [];
	foreach ($_POST as $key=>$value) {
		$winners[] = explode(",", $value);
	}
	handle_winner_create ($sellerid, $serviceid, $winners);
	return; 
} else if (!isset($_POST['key'])) {
	die("Error! No key found!");
}

$params = array_map(
	function ($x) { return rawurldecode($x); }, 
	explode(",", $_POST['key'])
);
?>
<html>
	<head>
		<title> Create Winners </title>
	</head>
	<body>
		<h1> <?= "Create Winners" ?> </h1>
		<?php
			$sellerid = $params[1];
			$serviceid = $params[0];

			$result = execute_sql_params("select bids by serviceid and userid", $params);
			if ($result != FALSE) {
				if (pg_num_rows($result) == 0) {
					echo "<h2> 0 rows </h2>";
					return;
				}
			} else {
				echo "Something Went Wrong!";
				return;
			}

			$i = 0;
			echo "<form method='post' id='input_form'>
				<input type = 'hidden' name = 'sellerid' value = '$sellerid'></input>
				<input type = 'hidden' name = 'serviceid' value = '$serviceid'></input>
				<table> <tr> <th>Select</th> <th>bidder</th> <th>petid</th> <th>bid amount</th></tr>";

			while ($bid = pg_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td><input type='checkbox' name=" . $i++ . " value='" . implode(",", $bid) . "'></input></td>";
				foreach ($bid as $info=>$value) {
					echo "<td>" . $value . "</td>";
				}
				echo "</tr>";
			}

			echo "</table><input type='submit' name='Submit'></form>";
		?>
		
	</body>
</html>


