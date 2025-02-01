## Instalasi

Pada instalasi ini bisa menggunakan 2 cara yaitu menggunakan Docker atau XAMPP/Laragon.

### Menggunakan Docker

1. Clone repository ini.
2. Jalankan perintah berikut:
    ```bash
    cp .env.example .env
    ```
3. Ubah pengaturan database pada file `.env` menjadi:
    ```
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=backend
    DB_USERNAME=laravel_user
    DB_PASSWORD=secret
    ```
4. Jalankan perintah ini di terminal:
    ```bash
    docker run --rm -v ${pwd}:/app composer install
    docker-compose build
    docker compose up
    docker-compose exec app php artisan key:generate
    ```
5. Reconnect Docker (ctrl+x), kemudian jalankan:
    ```bash
    docker compose up
    docker-compose exec app php artisan migrate --seed
    ```
6. Import dan Jalankan di Postman

    1. Import file `REST API.postman_collection.json` ke Postman.
    2. Jalankan request di Postman.

### Menggunakan XAMPP atau Laragon

#### Minimum Requirements

-   PHP versi 8.2
-   Composer versi 2

1. Clone repository ini.
2. Jalankan perintah berikut di terminal:
    ```bash
    cp .env.example .env
    ```
3. Sesuaikan konfigurasi database seperti pada file `.env`.
4. Buat database dengan nama `backend`.
5. Jalankan perintah berikut di terminal:

    ```bash
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    ```

6. Import dan Jalankan di Postman

    1. Import file `REST API.postman_collection.json` ke Postman.
    2. Jalankan request di Postman.
