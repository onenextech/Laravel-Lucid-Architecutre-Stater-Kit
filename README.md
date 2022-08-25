
##LARAVEL LUCID ARCHITECTURE STATER KIT

### ABOUT STATER KIT
This stater kit is based on lucid architecture (**[monolith](https://docs.lucidarch.dev/micro-vs-monolith/#monolith)**) for laravel framework
- **[Laravel Framework](https://laravel.com/)**
- **[Lucid Architecture](https://lucidarch.dev/)**

### REQUIREMENTS
- **[PHP](https://www.php.net/)** 8.0+

### INSTALLATION
```shell
git clone https://github.com/onenextech/Laravel-Lucid-Architecutre-Stater-Kit.git 
cd Laravel-Lucid-Architecutre-Stater-Kit
composer install
cp .env.example .env

php artisan key:generate
php artisan migrate
php artisan db:seed --class=ApplicationServiceSeeder

php artisan passport:install
```

### IMPORTANT
You will need to register your every service of you lucid app with your desired configurations to the lucid_application_providers array of the following config.
[config/core.php](./config/core.php)

#### And do the following step if only you've turned on the toggle_app_services config under core.php
And you may need to re-run [App\Database\Seeders\ApplicationServiceSeeder](./database/seeders/ApplicationServiceSeeder.php) 
