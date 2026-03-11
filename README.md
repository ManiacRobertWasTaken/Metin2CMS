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
| `REDIS_HOST` | `redis` | Redis hostname |

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
- Redis 7 (query caching)
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

**Performance**
- Redis caching for statistics, rankings, news, and 14 frequently called DB queries
- Automatic cache invalidation on data mutations (donate, news edit/delete, etc.)
- Rankings capped at 50,000 entries for players and guilds

**Infrastructure**
- Redis 7 container (no external access, 64MB maxmemory, LRU eviction)
- Docker setup for local development (Dockerfile, docker-compose.yml, entrypoint script)

VPS SETUP (Ubuntu + Docker + Cloudflare)
------------

### 1. Install Docker

```sh
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
logout
```

Log back in and verify:

```sh
docker --version
```

### 2. Clone the repo

```sh
git clone https://github.com/IonutPopescuRO/Metin2CMS.git
cd Metin2CMS
```

### 3. Configure docker-compose.yml

Edit the environment variables to match your setup:

```yaml
environment:
  - SITE_URL=https://yourdomain.com/
  - DB_HOST=mariadb
  - DB_USER=mt2
  - DB_PASS=your_db_password
  - REDIS_HOST=redis
  - SMTP_HOST=smtp.gmail.com
  - SMTP_PORT=465
  - SMTP_SECURE=ssl
  - SMTP_USER=your@gmail.com
  - SMTP_PASS=your_app_password
```

Make sure the `db_net` network matches your MariaDB container's network. If your database runs in a different Docker Compose project, check its network name:

```sh
docker network ls
```

Update the network name in `docker-compose.yml` if needed:

```yaml
networks:
  db_net:
    external: true
    name: your_db_network_name
```

### 4. Start the CMS

```sh
docker compose up -d --build
```

The CMS runs on port 8000 internally. Don't expose this port publicly — Cloudflare will handle it.

### 5. SSL certificate with Certbot

```sh
sudo apt install -y certbot
sudo certbot certonly --standalone -d yourdomain.com
```

Certbot will generate certificates at:
- `/etc/letsencrypt/live/yourdomain.com/fullchain.pem`
- `/etc/letsencrypt/live/yourdomain.com/privkey.pem`

Auto-renewal is enabled by default. Test it with:

```sh
sudo certbot renew --dry-run
```

### 6. Reverse proxy with Caddy (recommended)

Install Caddy:

```sh
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https
curl -1sLf 'https://dl.cloudflare.com/cloudflare-main.gpg' | sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudflare.com/caddy/stable/deb/debian/any-version/caddy-stable.list' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
sudo apt update && sudo apt install caddy
```

Edit `/etc/caddy/Caddyfile`:

```
yourdomain.com {
	reverse_proxy localhost:8000
	tls /etc/letsencrypt/live/yourdomain.com/fullchain.pem /etc/letsencrypt/live/yourdomain.com/privkey.pem
}
```

```sh
sudo systemctl restart caddy
```

### 7. Cloudflare DNS

1. Add your domain to Cloudflare
2. Set DNS A record: `@` → your VPS IP, **Proxied** (orange cloud)
3. SSL/TLS → set to **Full (Strict)**
4. Edge Certificates → enable **Always Use HTTPS**

### 8. Firewall

Only allow Cloudflare IPs and SSH:

```sh
sudo ufw default deny incoming
sudo ufw allow ssh
sudo ufw allow from 173.245.48.0/20 to any port 443
sudo ufw allow from 103.21.244.0/22 to any port 443
sudo ufw allow from 103.22.200.0/22 to any port 443
sudo ufw allow from 103.31.4.0/22 to any port 443
sudo ufw allow from 141.101.64.0/18 to any port 443
sudo ufw allow from 108.162.192.0/18 to any port 443
sudo ufw allow from 190.93.240.0/20 to any port 443
sudo ufw allow from 188.114.96.0/20 to any port 443
sudo ufw allow from 197.234.240.0/22 to any port 443
sudo ufw allow from 198.41.128.0/17 to any port 443
sudo ufw allow from 162.158.0.0/15 to any port 443
sudo ufw allow from 104.16.0.0/13 to any port 443
sudo ufw allow from 104.24.0.0/14 to any port 443
sudo ufw allow from 172.64.0.0/13 to any port 443
sudo ufw allow from 131.0.72.0/22 to any port 443
sudo ufw enable
```

### 9. Verify

- Visit `https://yourdomain.com` — should load the CMS
- Check headers: `curl -I https://yourdomain.com` — should see CSP, X-Frame-Options, etc.
- Admin panel: `https://yourdomain.com/admin/` (default: `admin` / `admin` — change immediately)

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
