Metin2CMS
=========
[![N|Solid](https://i.imgur.com/dS8151Q.png)](https://metin2cms.cf/v2)

[![Github All Releases](https://img.shields.io/github/downloads/IonutPopescuRO/Metin2CMS/total.svg)]()
[![GitHub release](https://img.shields.io/github/release/IonutPopescuRO/Metin2CMS.svg?color=%23f17e3f)]()
[![License](https://img.shields.io/github/license/IonutPopescuRO/Metin2CMS.svg?color=%230d7ebf)]()

Open-source CMS for Metin2 private servers. Includes player rankings, admin panel, donation system, vote4coins, news, and multi-language support.

REQUIREMENTS
------------

- PHP 8.3+
- MySQL / MariaDB
- Apache with mod_rewrite enabled

DOCKER
------------

```sh
docker-compose up -d
```

The CMS runs on port 8000 by default. Environment variables in `docker-compose.yml`:

| Variable | Default | Description |
|----------|---------|-------------|
| `SITE_URL` | `http://localhost:8000/` | Public URL |
| `DB_HOST` | `mariadb` | Database hostname |
| `DB_USER` | `mt2` | Database user |
| `DB_PASS` | `mt2` | Database password |
| `SMTP_HOST` | `smtp.gmail.com` | SMTP server |
| `SMTP_PORT` | `465` | SMTP port |
| `SMTP_SECURE` | `ssl` | SMTP encryption |
| `SMTP_USER` | | SMTP username |
| `SMTP_PASS` | | SMTP password |

MANUAL INSTALLATION
------------

Edit `config.php`:

```php
$host = "localhost";
$user = "root";
$password = "xxxxxx";

$site_url = "https://example.com/";

$SMTPAuth = true;
$SMTPSecure = "ssl";
$EmailHost = "smtp.gmail.com";
$emailPort = 465;
$email_username = "your@gmail.com";
$email_password = "xxxxxx";

$safebox_size = 1;
```

STACK
------------

- PHP 8.3 + PDO
- Bootstrap 5.3.3
- jQuery 3.7.1
- PHPMailer 6.9.3
- SQLite (settings storage)

LANGUAGES
------------
Available in 12 languages:

  - [en]	English
  - [ro] 	Romana
  - [fr] 	Francais
  - [pl] 	Polski
  - [pt-BR] 	Portugues (BR)
  - [es] 	Espanol
  - [it] 	Italiano
  - [tr] 	Turk
  - [hu] 	Magyar
  - [de] 	Deutsch
  - [el] 	Ellinika
  - [ar] 	Arabic

CHANGELOG v3.0
------------

**Security**
- CSRF protection on all POST forms and handlers
- Stored XSS fix in news (HTML sanitization with `sanitizeHtml()`)
- XSS fixes in news titles, timestamps, image src, player search, donate redirect, captcha, ranking
- DOM XSS fix in register.js (`.html()` replaced with `.text()`)
- SQL injection fix in `reset_char()` (parameterized query)
- Path traversal fixes in language install/delete and module/theme install
- PayPal txn_id replay protection and float comparison fix
- Session hardening (httponly, samesite, strict mode, fingerprint with SHA256, regeneration on login)
- Timing-safe comparisons for CSRF tokens and API keys (`hash_equals()`)
- Cookie security (httponly, samesite flags on all cookies)
- Content-Security-Policy, X-Frame-Options, X-Content-Type-Options, Referrer-Policy headers
- Deny-all `.htaccess` for `include/db/` directory
- Blocked `.git`, `.claude`, and sensitive files from web access
- Bounds validation for admin coin grants
- URL-encoded search input in redirect headers to prevent header injection
- Zip Slip protection and SSL enforcement

**Upgrades**
- PHP 8.3+ compatibility
- Bootstrap 4 alpha 3 → Bootstrap 5.3.3
- jQuery 2.2.4 → jQuery 3.7.1
- PHPMailer upgraded to 6.9.3
- Removed PclZip (using native ZipArchive)
- Removed Tether.js

**Infrastructure**
- Docker setup for local development (Dockerfile, docker-compose.yml, entrypoint script)

### Preview
<details><summary>CLICK ME</summary>
<p>
	<img src="https://i.imgur.com/EAR2Jc1.png"></img>
	<img src="https://i.imgur.com/PMnWEUy.png"></img>
	<img src="https://i.imgur.com/y4ivCJu.png"></img>
	<img src="https://i.imgur.com/GZgQ2tR.png"></img>
	<img src="https://i.imgur.com/1rRl1a5.png"></img>
	<img src="https://i.imgur.com/4884Z6K.png"></img>
	<img src="https://i.imgur.com/PC7CL34.png"></img>
	<img src="https://i.imgur.com/YSoe3CM.png"></img>
	<img src="https://i.imgur.com/J3zrrYK.png"></img>
</p>
</details>
