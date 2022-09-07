
###LARAVEL LUCID ARCHITECTURE STATER KIT

### ABOUT STATER KIT
This stater kit is based on lucid architecture (**[monolith](https://docs.lucidarch.dev/micro-vs-monolith/#monolith)**) for laravel framework.
- **[Laravel Framework](https://laravel.com/)**
- **[Lucid Architecture](https://lucidarch.dev/)**

### REQUIREMENTS
- **[PHP](https://www.php.net/)** 8.1^

### INSTALLATION
```shell
git clone https://github.com/onenextech/Laravel-Lucid-Architecutre-Stater-Kit.git 
cd Laravel-Lucid-Architecutre-Stater-Kit
cp .env.example .env #Don't forget to configure your .env file
composer install


php artisan key:generate
php artisan migrate
php artisan db:seed --class=ApplicationServiceSeeder

php artisan passport:install
```


Add the following line to your shell profile (~/.bash_profile, ~/.bashrc, ~/.zshrc), lucid & pint command will be usable in your application.
```shell
export PATH="./vendor/bin:$PATH"
```
(~/.bash_profile, ~/.bashrc, ~/.zshrc)

###Lucid
```shell
lucid ...
```

###Linting
```shell
pint
```


### IMPORTANT
- You will need to register your every service of you lucid app with your desired configurations to the lucid_application_providers array of the following config.
[config/core.php](./config/core.php).

- Do explore available commands at [Lucid Cli Reference](https://docs.lucidarch.dev/cli/) and use artisan command if only lucid can't provide what you need.


#### And do the following step if only you've turned on the toggle_app_services config under core.php.
You may need to re-run [App\Database\Seeders\ApplicationServiceSeeder](./database/seeders/ApplicationServiceSeeder.php) to reflect the changes.

For the purpose of enhancing application performance, we use registration of Application Services from cache + database approach here.
If you feel application services are not registered as expected, please run
```shell
php artisan cache:clear
```

### API DOCUMENTATION

We use the Scribe to generate the API documentation. So, please do visit [Scribe documentation](https://scribe.knuckles.wtf/laravel) website to see the documentation and follow the instructions.

```shell
php artisan scribe:generate     
```

### NOTES

##### Passport
Laravel passport doesn't allow as to disable its oauth routes by default, and I had to disable it by overriding its provider with [App\Providers\PassportServiceProvider](./app/Providers/PassportServiceProvider.php)
However, please feel free to toggle the registration of the passport oauth routes in above provider.



