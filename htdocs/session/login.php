<?php
include_once __DIR__ . "/../script/required.php";
if (has_username ()) {
	redirect5s('/session/profile.php');
	return;
}
?>
<html>
	<head>
		<title> Login </title>
	</head>
	<body>
		<form id="login-form" method="post">
			<ul>
				<li><label>Username: </label><input type="text" name="username"></li>
				<li><label>Password: </label><input type="password" name="password"></li>
				<li><input type="submit" name="login"></li>
			</ul>
		</form>
		<?php 
		if (isset($_POST["login"])) {
			if (is_valid_user($_POST['username'], $_POST['password'])) {
				echo "Logging in.... Redirect in 5s";
				set_credential($_POST['username']);
				redirect5s("/session/profile.php");
			} else {
				echo "<p> Invalid Username/Password </p>";
			}
		}
		?>
	</body>
</html>