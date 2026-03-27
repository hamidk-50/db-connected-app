# Apache Setup (bitcore.ca)

This project includes Apache templates for Homebrew Apache on macOS.

## Files in this repo

- `deploy/apache/httpd.conf.snippet`
- `deploy/apache/bitcore.ca.conf`

## 1) Install services

```bash
brew install httpd php
```

## 2) Apply Apache configs

Backup current files:

```bash
cp /opt/homebrew/etc/httpd/httpd.conf /opt/homebrew/etc/httpd/httpd.conf.bak
cp /opt/homebrew/etc/httpd/extra/httpd-vhosts.conf /opt/homebrew/etc/httpd/extra/httpd-vhosts.conf.bak
```

Then merge the directives from `deploy/apache/httpd.conf.snippet` into:

- `/opt/homebrew/etc/httpd/httpd.conf`

And copy the vhost template:

```bash
cp deploy/apache/bitcore.ca.conf /opt/homebrew/etc/httpd/extra/httpd-vhosts.conf
```

## 3) Laravel app URL

Set in `.env`:

```dotenv
APP_URL=http://bitcore.ca:8080
```

Clear config cache:

```bash
php artisan config:clear
```

## 4) Start and validate Apache

```bash
/opt/homebrew/opt/httpd/bin/httpd -t
brew services restart httpd
```

## 5) Local domain mapping (for local testing)

```bash
echo "127.0.0.1 bitcore.ca www.bitcore.ca" | sudo tee -a /etc/hosts
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder
```

## 6) Verify

```bash
curl -I -H "Host: bitcore.ca" http://127.0.0.1:8080
```

Expected status: `HTTP/1.1 200 OK`
