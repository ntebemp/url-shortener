# 🔗 URL Shortener – Laravel

Ce projet est un raccourcisseur d’URL simple, construit avec Laravel.

## ⚙️ Installation

1. Clone le dépôt :
   ```bash
   git clone https://github.com/ntebemp/url-shortener.git
   cd url-shortener

2. Installe les dépendances :
   ```bash
   composer install

3. Configure l'environnement :
    ```bash
    cp .env.example .env
    php artisan key:generate


4. Crée la base de données MySQL nommée url_shortener_db, puis rempli la base de données avec des données de test:
    ```bash
    php artisan migrate --seed


5. Lance le serveur :
    ```bash
    php artisan serve