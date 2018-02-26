<?php
include_once "../db/db.php";
?>
<html>
	<head>
		<title> Create Transaction </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> Create Transaction </h1>
		<form name="createTrans" method="post" onsubmit="return createTransOnSubmit(this);"> 
			<ul>
				<li><label>Transaction Id: </label><input type="text" name="tId"/></li>

				<li><label>Username: </label><input type="text" name="uId"/><br/></li>

				<li><label>Date: </label><input type="text" name="tDate"/><br/></li>

				<li><label>Type: </label><textarea type="text" name="tType"/></textarea><br/></li>

				<li><label>Amount: </label><textarea type="text" name="tAmount"/></textarea><br/></li>

				<li><input type='submit'></input></li>
			</ul>
		</form>
		<script>
			var createTransOnSubmit = createOnSubmit("Trans");
		</script>
	</body>
</html>