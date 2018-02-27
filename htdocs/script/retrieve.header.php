<?php
include_once __DIR__ . "/required.php";

function print_retrieve_header ($file) {
	$pageinfo = extract_page_info ($file);
	$tablename = gettablename($pageinfo);
	$extra = getextra ($pageinfo);
	$title = gettitle($pageinfo);
	$pk = get_primary_key($tablename, true);

	echo '<title>'. $title .'</title>
		<script src = "../script/helpers.js" type="text/javascript"></script>
		<script type="text/javascript">
			updateOnClick = makeRetrievedOnClick("/petcation/update/'.$tablename.'.update'.$extra.'.php",'.$pk.');
			deleteOnClick = makeRetrievedOnClick("/petcation/delete/'.$tablename.'.delete'.$extra.'.php",'.$pk.');
		</script>';

	return ['pageinfo'=> $pageinfo, "pk"=> $pk];
}

?>
