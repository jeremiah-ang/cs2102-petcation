<?php
include_once __DIR__ . "/../script/required.php";
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
			$petids = get_petids(get_username());
			CREATE_UPDATE_TABLE ($_POST, "update bids with key", $crud, $tablename, ["petids" => $petids]);
			handle_submit ($_POST, $crud, "update bid");
		?>
		<script>
			var formOnSubmit = createOnSubmit(<?= "'" . $tablename . "'" ?>);
			form = document.getElementById('input_form');
			input_texts = form.getElementsByTagName("input");
			for (var i = 0; i < input_texts.length; i++) {
				if (input_texts[i].name != "5amount")
					console.log(input_texts[i].readOnly = true);
			}
			form.getElementsByTagName("textarea")[0].readOnly = true;
			// form.getElementsByTagName("select")[0].disabled = true;
		</script>
	</body>
</html>