#!/bin/bash
#天津盛世房源追加 crontab执行不需要nohup
nohup php /var/www/Laravel_admin_study/phpspider/app/House/tianjin_lianjia_shengshi.php >> /tmp/scan_tianjin_lianjia_shengshi.log 2>&1 &