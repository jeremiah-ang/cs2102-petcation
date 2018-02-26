<?php
$QUERIES = [
	"select * users" => "SELECT * FROM users;",
	"select * pets" => "SELECT * FROM pets;",
	"select * bids" => "SELECT * FROM bids;",
	"select * service" => "SELECT * FROM service;",
	"select * wallet" => "SELECT * FROM wallet;",
	"select * trans" => "SELECT * FROM trans;",
	"select * winners" => "SELECT * FROM winners;",

	"select * from user with key" => "SELECT * FROM users WHERE userid = $1 AND username = $2",
	"select * from pets with key" => "SELECT * FROM pets WHERE petid = $1 AND userid = $2"

]
?>