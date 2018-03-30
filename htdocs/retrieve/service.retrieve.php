<?php
include_once __DIR__ . "/../script/retrieve.header.php";

if (isset($_POST['key'])) {
	$key = explode(",", $_POST['key']);
	$serviceid = $key[0];
	$userid = $key[1];
}
?>
<html>
	<head>
		<title> Service </title>
	</head>
	<body>
		<h1> Service </h1>
		<?php
			retrieve_table("select service by userid and serviceid", 
				[$userid, $serviceid], [], NULL,);
		?>
	</body>
</html>
