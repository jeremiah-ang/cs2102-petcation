psql -d petcation < ../sql/CustomDataType.sql
psql -d petcation < ../sql/CreateTables.sql
psql -d petcation < ../sql/TriggersFunctions.sql
psql -d petcation < ../sql/TriggersFunctionsDeletesUpdates.sql

python setup.py

psql -d petcation -c "select * from users"
psql -d petcation -c "select * from pets"
psql -d petcation -c "select * from service"