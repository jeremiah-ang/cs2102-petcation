<?php
include_once __DIR__ . "/required.php";

$USERNAME_KEY = 'username';

function is_valid_user($username, $password) {
	$cursor = execute_sql_params("is valid user", [$username, $password]);
	if (!is_null($cursor)) {
		return pg_num_rows($cursor) == 1;
	}
}

function set_credential ($username) {
	global $USERNAME_KEY;
	$_SESSION[$USERNAME_KEY] = $username;
}
function has_username () { global $USERNAME_KEY; return isset($_SESSION[$USERNAME_KEY]); }
function check_credential () {
	if (!has_username()) {
		redirect5s("/", 0);
	}
}
function get_username ($as_userid=false) { 
	global $USERNAME_KEY; 
	return (has_username()) 
			? ($as_userid) 
				? ['userid' => $_SESSION[$USERNAME_KEY]] 
				: $_SESSION[$USERNAME_KEY] 
			: NULL; 
}

function is_admin () {
	if (has_username()) {
		$result = execute_sql_params("user is admin", get_username(true));
		if (pg_num_rows($result) == 1) {
			return true;
		}
	} 
	return false;
}
?>