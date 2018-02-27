<?php
include_once __DIR__ . "/../script/required.php";
check_credential();
$pageinfo = extract_page_info (__FILE__);
$crud = $pageinfo[1];
$tablename = $pageinfo[0];
$title = $pageinfo[2];
?>
<html>
	<head>
		<title> <?= $title ?> </title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
	</head>
	<body>
		<h1> <?= $title ?> </h1>

		<?php
			$values = make_values ("petid", "pets next id");
			echo CREATE_TABLE($crud, $tablename, $values);
			handle_submit ($_POST, $crud)
		?>
		<script>
			var formOnSubmit = createOnSubmit(<?= "'" . $tablename . "'" ?>);
		</script>
	</body>
</html>
