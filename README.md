# E-Commerce API

Technical test API Checkout & Auth

## Persyaratan Sistem

Pastikan Anda memiliki persyaratan berikut sebelum memulai:

- PHP >= 8.2
- Composer
- MySQL

## Langkah Instalasi

### 1. Clone Reposity
Clone repository ke direktori lokal Anda menggunakan perintah berikut:
```bash
    https://github.com/hawary-id/ecommerce-api.git
```    

### 2. Masuk Direktori Proyek
```bash
    cd ecommerce-api
```

### 3. Install Dependencies
```bash
    composer install
```

### 4. Salin File `.env`
```bash
    cp .env.example .env
```

### 5. Generat Application Key
```bash
    php artisan key:generate
```

### 6. Konfigurasi Cache
```bash
    php artisan config:cache
```

### 7. Migrasi Database & Seeder
```bash
    php artisan migrate:fresh --seed
```

### 8. Generate Passport Keys
```bash
    php artisan passport:install --uuids
```

### 9. Menjalankan Applikasi
```bash
    php artisan serve
```

## License

Proyek ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
