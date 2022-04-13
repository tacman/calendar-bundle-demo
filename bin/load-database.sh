db=ccal
echo "drop database if exists $db; create database $db; grant all privileges on database $db to main; ALTER DATABASE $db OWNER TO main "  | sudo -u postgres psql
echo "grant all privileges on database $db to main; "  | sudo -u postgres psql

# stop on error
set -e
set -v
bin/console doctrine:schema:update --force

#git clean -f migrations/
#php bin/console make:migration
#exit 1;

#php bin/console doctrine:migration:migrate -n
php bin/console doctrine:fixtures:load -n

