# laravel-announcement-v1
### An Announcement Manager for Laravel Project v1

-------

## Requires
- [laravel/framework](https://github.com/laravel/framework/) v9.x
- [thebachtiarz/laravel-toolkit-v1](https://github.com/thebachtiarz/laravel-toolkit-v1/) v2.x
- [thebachtiarz/laravel-auth-v1](https://github.com/thebachtiarz/laravel-auth-v1/) v2.x

## Installation
- composer config (only if you have access)
```bash
composer config repositories.thebachtiarz/laravel-announcement-v1 git git@github.com:thebachtiarz/laravel-announcement-v1.git
```

- install repository
```bash
composer require thebachtiarz/laravel-announcement-v1
```

- vendor publish
``` bash
php artisan vendor:publish --provider="TheBachtiarz\Announcement\AnnouncementServiceProvider"
```

- register the REST API into -> **app/Providers/RouteServiceProvider.php**
```bash
Route::prefix(tbtoolkitconfig('app_prefix'))
    ->middleware(['api'])
    ->namespace($this->namespace)
    ->group(tbannrouteapi());
```

-------
## Feature

> sek males nulis cak :v
-------
