# 🔗 URL Shortener – Laravel

This project is a simple URL shortener built with Laravel.

## ⚙️ Installation

1. Clone the repository :
   ```bash
   git clone https://github.com/ntebemp/url-shortener.git
   cd url-shortener

2. Install dependencies :
   ```bash
   composer install

3. Configure environment :
    ```bash
    cp .env.example .env
    php artisan key:generate


4. Create the MySQL database named url_shortener_db, then populate the database with test data :
    ```bash
    php artisan migrate --seed


5. Launch server :
    ```bash
    php artisan serve