<?php
include_once __DIR__ . "/../script/required.php";
$pageinfo = extract_page_info (__FILE__);
$crud = $pageinfo[1];
$tablename = $pageinfo[0];
$title = $pageinfo[2];

handle_submit ($_POST, $crud, "insert bid on conflict");
if (!isset($_POST["key"])) {
	redirect5s("/retrieve/service.retrieve.all.php");
	return;
}

$bidid = get_next_id("bid next id");
$values = explode(",", $_POST["key"]);
$buyerid = get_username();
$sellerid = $values[1];
$serviceid = $values[0];
$service = get_service($sellerid, $serviceid);
if (is_null($service)) {
	echo "<h2> Error! No Service Found! </h2>";
	kill();
}

$startdate = $service['startdate'];
$enddate = $service['enddate'];
$pettype = $service['pettype'];
$description = $service['description'];

$petids = get_petids(get_username(), $pettype);
$walletlimit = get_walletlimit(get_username());
?>
<html>
	<head>
		<title> <?= $title ?> </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> <?= $title ?> </h1>

		<?php
			if (count($petids) == 0) {
				echo "<h2> Service: $serviceid, Seller: $sellerid </h2><p> You have no $pettype </p>";
			} else {
				echo CREATE_TABLE($crud, $tablename, [
					"bidid" => $bidid,
					"buyerid" => $buyerid,
					"sellerid" => $sellerid,
					"serviceid" => $serviceid,
					"petids" => $petids,
					"_startdate" => $startdate,
					"_enddate" => $enddate,
					"_pettype" => $pettype,
					"_description" => $description
				], [
					"amount" => ["max = '$walletlimit'"]
				]);
			}
			
			
		?>
		<script>
			var formOnSubmit = createOnSubmit(<?= "'" . $tablename . "'" ?>);
		</script>
	</body>
</html>
