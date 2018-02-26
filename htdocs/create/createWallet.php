<?php
include_once "../db/db.php";
?>
<html>
	<head>
		<title> Create Wallet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Wallet </h1>
		<form name="createWallet" method="post" onsubmit="return createWalletOnSubmit(this);"> 
			<ul>
				<li><label>TopUp Id: </label><input type="text" name="topUpId"/></li>

				<li><label>User Id: </label><input type="text" name="userId"/><br/></li>

				<li><label>amount: </label><input type="text" name="amount"/><br/></li>

				<li><label>datetime: </label><textarea type="text" name="timeDate"/></textarea><br/></li>

				<li><input type='submit'></input></li>
			</ul>
		</form>
		<script>
			var createWalletOnSubmit = createOnSubmit("Wallet");
		</script>
	</body>
</html>