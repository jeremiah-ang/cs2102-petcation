<?php
$CUSTOM_QUERIES = [
	"is valid user" => "select * from users where userid = $1 and password = $2 and status = 'ACTIVE'",

	"select 1 user" => "select userid, username, credits from users where userid = $1",

	"bid next id" => "select case count(bidid) when 0 then 1 else max(bidid) + 1 end as next from bids",

	"select pets of user" => "select petid, petName from pets where userid = $1",

	"bid bought" => "select * from bids where buyerid = $1 and winner = FALSE order by petid",

	"wallet limit" => "select credits from users where userid = $1",

	"select bids by serviceid and userid" => "
		select buyerid, petid, sum(amount) as amt from bids 
		where serviceid = $1 
		and sellerid = $2 
		group by (buyerid, petid, sellerid, serviceid)",

	"select unwon services" => "select * from service where userid = $1 and winner = FALSE",

	"select all unwon services" => "
		SELECT s.serviceid, s.userid, t.typedescription, s.startdate, s.enddate 
		from service s inner join pettype t
		ON s.pettype = t.typeid
		where NOT EXISTS (
			select 1
			from bids b
			where s.serviceid = b.serviceid 
			AND s.userid = b.sellerid 
			AND winner = TRUE
		) 
		AND startdate > now()
		AND userid <> $1
		AND status = 'ACTIVE'",

	"insert into wallet" => "select topup($1, $2)",

	"create winner" => "UPDATE 	bids
        SET 	winner 		= TRUE
        WHERE 	sellerid 	= $1 
        AND 	serviceid 	= $2
        AND 	buyerid 	= $3
        AND 	petid 		= $4",

	"refund" => "select refund_bids($1, $2)",

	"select service won by userid" => "select * from bids where buyerid = $1 and winner = TRUE",

	"select trans by userid" => "select * from trans where userid = $1",

	"get petids for bidding" => "
		select * from pets 
		where userid = $1 
		and pettype = $2 
		and (userid, petid) NOT IN (
			select buyerid, petid 
			from bids w inner join service s 
			on s.userid = w.sellerid 
			and s.serviceid = w.serviceid 
			where winner = TRUE 
			AND s.startdate < now() 
			AND s.enddate > now())",

	"get pettype of service" => "select pettype from service s where s.userid = $1 and s.serviceid = $2",

	"get service" => "select startdate, enddate, pettype, description from service where userid = $1 AND serviceid = $2",

	"insert bid on conflict" => "insert into bids (sellerid, serviceid, buyerid, petid, amount) values ($1, $2, $3, $4, $5) ON CONFLICT (sellerid, serviceid, buyerid, petid) DO UPDATE set amount = $5",

	"search services" => "
		select serviceid, userid, pettype, startdate, enddate
		from service 
		where userid <> $1
		AND pettype = $2
		AND startdate >= $3
		AND enddate <= $4
		AND status = 'ACTIVE'
	",

	"select service by userid and serviceid" => "
		select userid, pettype, startdate, enddate, description
		from service
		where serviceid = $2
		and userid = $1
	",

	"user is admin" => "select * from users
						where userid = $1
						and isAdmin = TRUE",

	"select * users" => "select * from users where isAdmin = FALSE and status <> 'INACTIVE'",

	"get pet types" => "select * from pettype",

	"select pets by userid" => "
		SELECT petid, userid, petname, typedescription PetType
		FROM pets p inner join pettype t 
		ON p.pettype = t.typeid 
		WHERE userid = $1 
		AND status = 'ACTIVE'
		ORDER BY petid
	",

	"select service by userid" => "
		SELECT 	s.serviceid, 
				s.userid, 
				t.typedescription as PetType, 
				s.startdate, 
				s.enddate
		FROM service s inner join pettype t
		ON s.pettype = t.typeid
		WHERE userid = $1
		AND status = 'ACTIVE'
		ORDER BY serviceid
	",

	"update bids with key" => "
		SELECT 	b.serviceid,
				b.sellerid,
				b.buyerid,
				b.petid as petids,
				t.typedescription as _pettype,
				s.startdate as _startdate,
				s.enddate as _enddate,
				s.description as _description,
				b.amount
		FROM (bids b inner join service s 
		ON b.serviceid = s.serviceid 
		AND b.sellerid = s.userid) inner join pettype t
		ON s.pettype = t.typeid 
		WHERE b.buyerid = $1
		AND b.petid = $2
		AND b.sellerid = $3
		AND b.serviceid = $4
	",

	"update bid" => "
		UPDATE bids
		SET amount = $5
		WHERE 	sellerid = $1
		AND 	serviceid = $2
		AND 	buyerid = $3
		AND 	petid = $4
	",

	"get all petids" => "
		SELECT petid, petname
		FROM pets
		WHERE userid = $1
		AND status = 'ACTIVE'
	",

	"buyer stats" => "
		SELECT pt.typeDescription, 
				case count(b.amount) 
				when 0 then 0
				else round(avg(b.amount), 1) end as Average, 
				count(b.amount) 
		FROM pettype pt left outer join 
				(bids b inner join service s
				ON b.sellerid = s.userid 
				AND b.serviceid = s.serviceid)
		ON pt.typeid = s.pettype 
		AND b.winner = TRUE 
		GROUP BY pt.typeId
	",

	"ranked pet type owned" => "
		WITH activepets AS (
			SELECT * FROM pets WHERE status = 'ACTIVE'
		)

		SELECT pt.typedescription as pettype, count(p.petid) as numOfPet
		FROM pettype pt left outer join activepets p
		ON p.pettype = pt.typeid 
		GROUP BY pt.typeid
		ORDER BY numOfPet DESC
	",

	"ranked pet type served" => "
		WITH activeservice AS (
			SELECT * FROM service WHERE status = 'ACTIVE'
		)

		SELECT pt.typedescription as pettype, count(p.serviceid) as numOfPet
		FROM pettype pt left outer join activeservice p 
		ON p.pettype = pt.typeid 
		GROUP BY pt.typeid
		ORDER BY numOfPet DESC
	"
];

$QUERIES = [];
generate_select_statements($QUERIES);
generate_keys_statements($QUERIES);

$tables = [
		"pets" => "petid",
		"service" => "serviceid"
	];
generate_select_by_user_statements($tables, $QUERIES);

$QUERIES = array_merge($QUERIES,$CUSTOM_QUERIES);
?>