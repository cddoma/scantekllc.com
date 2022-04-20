#!/usr/bin/bash
/usr/bin/bash /var/www/dev/scantek/stop_octane.sh
/usr/bin/bash /var/www/dev/scantek/cache_clear.sh
/usr/bin/php /var/www/dev/scantek/artisan octane:start --watch --host=127.0.0.1 --port=8080 > /var/log/octane/scantek.log