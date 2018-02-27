<?php
include_once __DIR__ . "/../script/required.php";
$pageinfo = extract_page_info (__FILE__);
$crud = $pageinfo[1];
$tablename = $pageinfo[0];
$title = $pageinfo[2];

handle_submit ($_POST, $crud);
if (!isset($_POST["key"])) {
	redirect5s("/retrieve/service.retrieve.all.php");
	return;
}

$bidid = get_next_id("bid next id");
$values = explode(",", $_POST["key"]);
$buyerid = get_username();
$sellerid = $values[1];
$serviceid = $values[0];
$petids = get_petids(get_username());
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
			echo CREATE_TABLE($crud, $tablename, [
				"bidid" => $bidid,
				"buyerid" => $buyerid,
				"sellerid" => $sellerid,
				"serviceid" => $serviceid,
				"petids" => $petids
			], [
				"amount" => ["max = '$walletlimit'"]
			]);
			
		?>
		<script>
			var formOnSubmit = createOnSubmit(<?= "'" . $tablename . "'" ?>);
		</script>
	</body>
</html>
