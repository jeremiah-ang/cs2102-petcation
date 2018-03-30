<?php
include_once __DIR__ . "/../script/required.php";
include_once __DIR__ . "/../script/retrieve.header.php";
check_credential();
$username = get_username();
?>

<!DOCTYPE html>
<html>
<head>
	<title><?= $username ?></title>
	<?php
		$page_info = print_retrieve_header ("users.retrieve.php");
	?>
</head>
<body>
	<h1> <?= $username ?> </h1>
	<?php
	retrieve_table("select 1 user", [$username], [], "updateOnClick(this)", null);
	?>
	<table id = 'user-table'>
	</table>

	<ul>
		<li><a href="/petcation/create/pets.create.php" id="create-pet">Create Pet</a></li>
		<li><a href="/petcation/retrieve/pets.retrieve.php" id="retrieve-pet">Retrieve Pet</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/create/service.create.php" id="create-service">Create Service</a></li>
		<li><a href="/petcation/retrieve/service.retrieve.my.php" id="retrieve-service">Retrieve Service</a></li>
		<li><a href="/petcation/retrieve/service.won.retrieve.php" id="retrieve-service">Retrieve Service Won</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/create/wallet.create.php" id="create-wallet">Topup Wallet</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/retrieve/bids.retrieve.buy.php" id="retrieve-bids">Bids Bought</a></li>
	</ul>
</body>
</html>