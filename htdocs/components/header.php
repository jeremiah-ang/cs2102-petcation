<?php
include_once __DIR__ . "/../script/required.php";
?>
<a href="/petcation/"> Home </a>
<?php
if (isset($_SESSION['username'])) {
?>
	<a href="/petcation/session/profile.php"> Profile page </a>
	<a href="/petcation/session/logout.php"> Logout </a>
<?php
} else {
?>
	<a href="/petcation/session/login.php"> Login </a>
	<a href="/petcation/create/users.create.php"> Register </a>
<?php
}
?>