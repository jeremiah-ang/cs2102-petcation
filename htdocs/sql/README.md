# SQLS

## CreateTables.sql

[Update] Changed usertype to isAdmin
[Update] Removed usertypes, change datatype of usertype to boolean (True = Admin, False = Normal User)
[Update] Removed Winners, add column to bids, winner, datatype: boolean (True = Is a winner, False = Not a winner)
[Update] Added check to service start/end date

[Outdated] Created 2 table 
- pettypes (typeid, typedescription)
- usertypes (typeid, typedescription)

[Outdated] Modified 3 table
- users(type) references usertypes(typeid)
- pets(type) references pettypes(typeid)
- service(pettype) references pettypes(typeid)

[Outdated] Create a few default entries 
- Admin user account; username: admin, password: admin
- Standard pettypes; dog, bird, cat, rabbit, hamster

## TriggerFunctions.sql

[Update] Removed create_winner function, creating winner is now a single sql query instead
[Update] Refund function, winner bids not deleted. Service provider will be credited only at this step

## TriggerFunctionsDeletesUpdates.sql 

[Update] Removed Start/End date triggers 
[Update] Removed Winner Table Triggers
[Update] Implemented Prevent update pet that has bidded