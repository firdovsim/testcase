# Откройте терминал и выполните команду
crontab -e

# Добавьте следующую строку в cron для запуска каждый день в определенное время
0 8 * * * /usr/bin/php /path_to_your_file/send_notifications.php
