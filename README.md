# Преглед на печата | pregled.kaloyan.info

Daily aggregation of the press-reviews from Capital.bg and Investor.bg, which are then stored locally and emailed to me

## Crontab
```
##
## PREGLED
##
*/10 22,23,0 * * 1-6 /usr/local/php53/bin/php /home/kktsvetkov/pregled.kaloyan.info/investor_bg.php >> /home/kktsvetkov/pregled.kaloyan.info/investor_bg.log
35 22,23,0 * * 1-6 /usr/local/php53/bin/php /home/kktsvetkov/pregled.kaloyan.info/kapital_daily.php >> /home/kktsvetkov/pregled.kaloyan.info/kapital_daily.log
```
