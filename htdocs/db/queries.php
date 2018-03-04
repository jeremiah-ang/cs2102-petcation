<?php
$QUERIES = [
	"is valid user" => "select * from users where userid = $1",

	"select 1 user" => "select * from users where userid = $1",

	"bid next id" => "select case count(bidid) when 0 then 1 else max(bidid) + 1 end as next from bids",

	"select pets of user" => "select petid, petName from pets where userid = $1",

	"bid bought" => "select * from bids where buyerid = $1 order by petid",

	"wallet limit" => "select credits from users where userid = $1",

	"select bids by serviceid and userid" => "select buyerid, petid, sum(amount) as amt from bids where serviceid = $1 and sellerid = $2 group by (buyerid, petid, sellerid, serviceid)",

	"insert into winners" => "insert into winners (serviceid, sellerid, buyerid, petid, amount) values ($1,$2,$3,$4,$5)",

	"select unwon services" => "select * from service where userid = $1 and (userid, serviceid) NOT IN (select userid, serviceid from winners)",

	"select all unwon services" => "select * from service where (userid, serviceid) NOT IN (select sellerid, serviceid from winners)"
];

generate_select_statements($QUERIES);
generate_keys_statements($QUERIES);

$tables = [
		"pets" => "petid",
		"wallet" => "topupid",
		"service" => "serviceid"
	];
generate_select_by_user_statements($tables, $QUERIES);
?>