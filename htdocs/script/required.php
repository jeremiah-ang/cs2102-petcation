<?php
session_start();
require_once __DIR__ . "/../db/db.php";
require_once __DIR__ . "/../db/forms.php";
require_once __DIR__ . "/../components/header.php";
include_once __DIR__ . "/session.helper.php";
include_once __DIR__ . "/form.helper.php";

function extract_page_info ($filename) {
	$op = explode(".", basename($filename, ".php"));
	$title = ucwords($op[1] . " " . $op[0]);
	$op[] = $title;
	return $op;
}

function gettitle($page_info) { return (count($page_info) == 4) ? $page_info[3] : $page_info[2]; }
function gettablename($page_info) { return $page_info[0]; }
function getcrud($page_info) { return $page_info[1]; }
function getextra($page_info) { return (count($page_info) == 4) ? ".".$page_info[2] : ""; }

function redirect5s ($url, $delay=0) {
	return header("Refresh: $delay; URL=/petcation$url");
}

?>