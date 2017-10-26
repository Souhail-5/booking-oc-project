echo "Clean cache and logs"
sudo chown -R webapp:webapp var/cache
sudo chown -R webapp:webapp var/logs
sudo rm -rf var/cache/*
sudo rm -rf var/logs/*
find var/ -type d -exec sudo chmod 777 {} \;
find var/ -type f -exec sudo chmod 777 {} \;

echo "Update parameters.yml"
sudo vim app/config/parameters.yml

echo "Load fixtures"
sudo sh utils/fixtures.sh
