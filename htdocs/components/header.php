<?php
include_once __DIR__ . "/../script/required.php";
?>

<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed" rel="stylesheet"/>
<link href="../style/style.css" rel='stylesheet'/>
<link href="./style/style.css" rel='stylesheet'/>
<div id = 'header'>
<a href="/petcation/" class = "header" id = "home"> Home </a>
<?php
if (isset($_SESSION['username'])) {
?>
	<a href="/petcation/session/profile.php" class = "header" id = "profile"> Profile page </a>
	<a href="/petcation/session/logout.php" class = "header" id = "logout"> Logout </a>
<?php
} else {
?>
	<a href="/petcation/session/login.php" class = "header" id = "login"> Login </a>
	<a href="/petcation/create/users.create.php" class = "header" id = "register"> Register </a>
<?php
}
?>
</div>