psql -d petcation < ../sql/CustomDataType.sql
psql -d petcation < ../sql/CreateTables.sql
psql -d petcation < ../sql/TriggersFunctions.sql
psql -d petcation < ../sql/TriggersFunctionsDeletesUpdates.sql

if [[ $# -ne 1 ]]; then
	#statements
	echo "Usage: run.sh [Test suite]"
	exit 0
fi

# python basic.test.py
python $1.test.py > output/$1.output.txt

psql -d petcation -c "select * from users" >> output/$1.output.txt
psql -d petcation -c "select * from service" >> output/$1.output.txt
psql -d petcation -c "select * from pets" >> output/$1.output.txt
psql -d petcation -c "select * from bids" >> output/$1.output.txt

cat output/$1.output.txt