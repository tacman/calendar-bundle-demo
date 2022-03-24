db=calendar_demo
echo "drop database if exists $db; create database $db; grant all privileges on database $db to main; ALTER DATABASE $db OWNER TO main "  | sudo -u postgres psql
echo "grant all privileges on database $db to main; "  | sudo -u postgres psql

# stop on error
set -e
set -v
#bin/console doctrine:schema:update --force

#git clean -f migrations/
#php bin/console make:migration
#exit 1;

php bin/console doctrine:migration:migrate -n
php bin/console app:load-locations
#APP_DEBUG=0 bin/console app:create-project joeb --name "Jardin Oaxaca" --startingAt=10000

bin/create-admins.sh
bin/console app:create-project joeb --name "Jardin Oaxaca" --startingAt=10000
bin/console app:create-project test --name "Test" --startingAt=10000


#heroku run "bin/console app:import-mysql -vv herbario  --dsn=\"mysql:host=156.67.74.1;dbname=u303262174_herbario\" --user=u303262174_herbarios "

# @todo: verify project roles!
bin/console app:create-member joeb tacman@gmail.com --roles PROJECT_ADMIN
bin/console app:create-member joeb  biolopga@gmail.com --roles PROJECT_ADMIN --roles PROJECT_GEO

bin/console app:create-member test  biolopga@gmail.com,espartanjavier@hotmail.com,leonlunaibeth@gmail.com,andressorianogarcia341@gmail.com,nolascomichael602@gmail.com --roles PROJECT_ADMIN --roles PROJECT_GEO
bin/console app:create-member joeb  biolopga@gmail.com,espartanjavier@hotmail.com,leonlunaibeth@gmail.com,andressorianogarcia341@gmail.com,nolascomichael602@gmail.com --roles PROJECT_ADMIN --roles PROJECT_GEO

#php bin/console app:import-mysql -vv herbario  --dsn="mysql:host=156.67.74.1;dbname=u303262174_herbario" --user=u303262174_herbarios --project joeb
bin/console app:excel joeb plantas --sheet 2 -vv
#bin/console app:excel joeb plantas --sheet 2 -vv


bin/console app:wiki https://www.wikidata.org/wiki/Q2342847 -vv
bin/console app:wiki https://www.wikidata.org/wiki/Q391624 -vv
bin/console app:wiki https://www.wikidata.org/wiki/Q157270 -vv

bin/console app:import-mysql -vv jardin  --dsn="mysql:host=156.67.74.1;dbname=u303262174_jardin" --user=u303262174_hostinger --project joeb --plants-first

#php bin/console app:import-mysql -vv herbario  --dsn="mysql:host=localhost;dbname=herbario" --user=root --project joeb
#heroku run "bin/console app:import-mysql -vv herbario  --dsn=\"mysql:host=156.67.74.1;dbname=u303262174_herbario\" --user=u303262174_herbarios "
#bin/console app:import-mysql -vv jardin  --dsn="mysql:host=uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=w3m4r5ko5gfzrfu9" --user=yquvgpnrpu5lpunwx

#u303262174_herbarios
#php bin/console app:import-mysql -vv jardin  --dsn="mysql:host=156.67.74.1;dbname=u303262174_jardin" --user=u303262174_hostinger

php bin/console app:post-import --wiki -v
php bin/console app:post-import --geo
bin/console app:excel joeb semillas
bin/console app:tax --limit 5000 -vv
bin/console app:tax --limit 5000 -vv
bin/console app:tax --limit 5000 -vv

echo "database loaded!"

exit 1;
#php bin/console app:import-mysql --type jardin "mysql:host=eanl4i1omny740jw.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=vzmh9kl9965yawf4" --user azw84v1hnm6gx5jc --password nfccdxrxa57w58zy
exit 1;


heroku run "bin/console app:import-mysql -vv herbario  --dsn=\"mysql:host=156.67.74.1;dbname=u303262174_herbario\" --user=u303262174_herbarios "
heroku run "bin/console app:import-mysql -vv jardin  --dsn=\"mysql:host=uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=w3m4r5ko5gfzrfu9\" --user=yquvgpnrpu5lpunw "

mysql://yquvgpnrpu5lpunw:s0b5eidxcc90fl41@uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/w3m4r5ko5gfzrfu9

#php bin/console app:import-mysql --type herbario "mysql:host=localhost;user=root;password=tt;dbname=herbario"
#php bin/console app:import-mysql --type jardin "mysql:host=localhost;user=root;password=tt;dbname=jardin" -vv

exit 1;

JAWSDB_URL=mysql://:nfccdxrxa57w58zy@:3306/vzmh9kl9965yawf4
nfccdxrxa57w58zy

# heroku, tac
JAWSDB_URL=mysql://azw84v1hnm6gx5jc:nfccdxrxa57w58zy@eanl4i1omny740jw.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/vzmh9kl9965yawf4


mysql -uroot -ptt --host=localhost jardin <  michael.sql
# load into heroku (beta)
mysql://yquvgpnrpu5lpunw:s0b5eidxcc90fl41@uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/w3m4r5ko5gfzrfu9

mysql -u yquvgpnrpu5lpunw -ps0b5eidxcc90fl41 --host=uyu7j8yohcwo35j3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com w3m4r5ko5gfzrfu9 <  michael.sql

#mysqldump -u azw84v1hnm6gx5jc -pbcx0bz7j7laqbg3a --host=eanl4i1omny740jw.cbetxkdyhwsb.us-east-1.rds.amazonaws.com vzmh9kl9965yawf4


#php bin/console app:import-mysql -vv herbario  --dsn="mysql:host=156.67.74.1;dbname=u303262174_herbario" \
#   --user=u303262174_herbarios --password=JavierNn3.1416


mysql -u root -p herbario < herbario.sql


#pgloader mysql://root:tt@127.0.0.1:3306/jardin postgresql://main:main@127.0.0.1:5432/jardin
#psql -u main jardin < ../mysql-postgresql-converter/pdatabase.sql
#python ../mysql-postgresql-converter/db_converter.py database.sql pdatabase.sql
#sed -i "s|0000-00-00|1800-01-01|g" pdatabase.sql
#
#sudo -u postgres psql -d jardin < pdatabase.sql
