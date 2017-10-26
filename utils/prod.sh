echo "Clean cache and logs"
sudo chown -R webapp:webapp var/cache
sudo chown -R webapp:webapp var/logs
sudo rm -rf var/cache/*
sudo rm -rf var/logs/*

echo "Update parameters.yml"
sudo vim app/config/parameters.yml

echo "Load fixtures"
sudo sh utils/fixtures.sh
