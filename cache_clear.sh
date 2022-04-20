#!/usr/bin/bash
/usr/bin/php /var/www/prod/scantek/artisan cache:clear
/usr/bin/php /var/www/prod/scantek/artisan view:clear
/usr/bin/php /var/www/prod/scantek/artisan view:cache
/usr/bin/php /var/www/prod/scantek/artisan config:clear
/usr/bin/php /var/www/prod/scantek/artisan config:cache
/usr/bin/php /var/www/prod/scantek/artisan route:clear
/usr/bin/php /var/www/prod/scantek/artisan route:cache