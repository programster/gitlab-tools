# Please do not manually call this file!
# This script is run by the docker container when it is "run"

# Ensure apache is not running as about to perform migrations.
service apache2 stop


# Run migrations on startup before the webserver has started
/usr/bin/php /var/www/my-site/scripts/migrate.php


# Start the webserver now everything is in place.
service apache2 start


# Start the cron service in the foreground
# We dont run apache in the FG, so that we can restart apache without container
# exiting.
cron -f
