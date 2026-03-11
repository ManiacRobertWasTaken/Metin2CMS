#!/bin/bash
set -e

cat > /var/www/html/config.php <<PHPEOF
<?php
	\$site_url = "${SITE_URL:-http://localhost:8000/}";

	\$host = "${DB_HOST:-host.docker.internal}";
	\$user = "${DB_USER:-mt2}";
	\$password = "${DB_PASS:-mt2}";

	\$SMTPAuth = true;
	\$SMTPSecure = "${SMTP_SECURE:-ssl}";
	\$EmailHost = "${SMTP_HOST:-smtp.gmail.com}";
	\$emailPort = ${SMTP_PORT:-465};

	\$email_username = "${SMTP_USER:-}";
	\$email_password = "${SMTP_PASS:-}";

	\$safebox_size = ${SAFEBOX_SIZE:-1};
PHPEOF

chown www-data:www-data /var/www/html/config.php

exec "$@"
