<?php
include_once __DIR__ . "/script/required.php";
?>

<html>
	<head>
		<title> Petcation </title>
	</head>
	<body>

		<h1> Petcation </h1>

		<ul> 
			<li><a href = '/petcation/search/search.php'> Search </a></li>
			<li><a href = '/petcation/retrieve/service.retrieve.all.php' id = 'all-services'> All Services </a></li>
			<li><a href = '/petcation/stats/stats.php'> Stats </a></li>
			<?php 
				if (is_admin()) {
					echo "<li><a href = '/petcation/retrieve/users.retrieve.php' id = 'all-users'> All Users </a></li>";
				}
			?>
		</ul>
		
	</body>
</html>