<?php
include_once __DIR__ . "/../script/required.php";
check_credential();
$username = get_username();
?>

<!DOCTYPE html>
<html>
<head>
	<title><?= $username ?></title>
</head>
<body>
	<h1> <?= $username ?> </h1>
	<?php
	$cursor = execute_sql_params("select 1 user", [$username]);
	$user = pg_fetch_assoc($cursor);
	?>
	<table>
		<tr>
			<th>Username</th>
			<th>Full Name</th>
			<th>Date of birth</th>
			<th>Address</th>
			<th>Wallet</th>
		</tr>
		<tr>
			<td><?= $user['userid'] ?></td>
			<td><?= $user['username'] ?></td>
			<td><?= $user['dateofbirth'] ?></td>
			<td><?= $user['address'] ?></td>
			<td><?= $user['credits'] ?></td>
		</tr>
	</table>

	<ul>
		<li><a href="/petcation/create/pets.create.php">Create Pet</a></li>
		<li><a href="/petcation/retrieve/pets.retrieve.php">Retrieve Pet</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/create/service.create.php">Create Service</a></li>
		<li><a href="/petcation/retrieve/service.retrieve.php">Retrieve Service</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/create/wallet.create.php">Topup Wallet</a></li>
		<li><a href="/petcation/retrieve/wallet.retrieve.php">Retrieve Wallet</a></li>
	</ul>

	<ul>
		<li><a href="/petcation/retrieve/bids.retrieve.buy.php">Bids Bought</a></li>
	</ul>
</body>
</html>