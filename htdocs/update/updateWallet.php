<?php
include_once __DIR__ . "/../script/required.php";
?>
<html>
	<head>
		<title> Create Wallet </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Wallet </h1>
		<form name="createWallet" method="post" onsubmit="return createWalletOnSubmit(this);">  
			<input type='hidden' name='table' value='Wallet'></input>
			<ul>
				<li><label>TopUp Id: </label><input type="text" name="topUpId"/></li>

				<li><label>User Id: </label><input type="text" name="userId"/><br/></li>

				<li><label>amount: </label><input type="text" name="amount"/><br/></li>

				<li><label>datetime: </label><textarea type="text" name="timeDate"/></textarea><br/></li>

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
			var createWalletOnSubmit = createOnSubmit("Wallet");
		</script>
	</body>
</html>