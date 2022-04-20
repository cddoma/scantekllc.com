#!/usr/bin/bash
/usr/bin/php /var/www/dev/scantek/artisan cache:clear
/usr/bin/php /var/www/dev/scantek/artisan view:clear
/usr/bin/php /var/www/dev/scantek/artisan view:cache
/usr/bin/php /var/www/dev/scantek/artisan config:clear
/usr/bin/php /var/www/dev/scantek/artisan config:cache
/usr/bin/php /var/www/dev/scantek/artisan route:clear
/usr/bin/php /var/www/dev/scantek/artisan route:cache