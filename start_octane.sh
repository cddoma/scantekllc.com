#!/usr/bin/bash
/usr/bin/bash /var/www/prod/scantek/stop_octane.sh
/usr/bin/git -C /var/www/prod/scantek pull
/usr/local/bin/composer update
/usr/local/bin/npm run prod
/usr/bin/bash /var/www/prod/scantek/cache_clear.sh
/usr/bin/php /var/www/prod/scantek/artisan octane:start --host=127.0.0.1 --port=8080 > /var/log/octane/scantek.log