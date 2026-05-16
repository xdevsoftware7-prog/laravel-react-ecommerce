# Project Setup Guide

## 1. Clone the Repository

```bash
git clone <repository-url>
```

Enter the project folder:

```bash
cd <project-folder>
```

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Node.js Dependencies

```bash
npm install
```

---

## 4. Create Environment File

### Windows

```bash
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Configure Database

Edit the `.env` file:

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 7. Run Database Migrations

```bash
php artisan migrate
```

If the project includes seeders:

```bash
php artisan db:seed
```

Or fresh install with seeders:

```bash
php artisan migrate:fresh --seed
```

---

## 8. Run Development Servers

### Option 1 — Separate Terminals

Backend:

```bash
php artisan serve
```

Queue:

```bash
php artisan queue:listen --tries=1
```

Frontend:

```bash
npm run dev
```

---

### Option 2 — Single Command

```bash
composer run dev
```

---

# Required PHP Extensions

Make sure the following extensions are enabled in `php.ini`:

```ini
extension=exif
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
```

Check enabled extensions:

```bash
php -m
```

---

# Important Notes

* Do NOT use `composer update` unless necessary.
* Do NOT use `npm update` unless necessary.
* Use `composer install` and `npm install` to keep dependency versions consistent with the project.
* This project uses the versions locked inside:

  * `composer.lock`
  * `package-lock.json`

---

# Useful Commands

Clear cache:

```bash
php artisan optimize:clear
```

Run queue worker:

```bash
php artisan queue:listen --tries=1
```

Build frontend for production:

```bash
npm run build
```
