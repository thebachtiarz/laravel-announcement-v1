# laravel-announcement-v1
### An Announcement Manager for Laravel Project v1

-------

### Requires
- [thebachtiarz/laravel-toolkit-v1](https://github.com/thebachtiarz/laravel-toolkit-v1/)

### Installation
- composer config (only if you have access)
```bash
composer config repositories.thebachtiarz/laravel-announcement-v1 git git@github.com:thebachtiarz/laravel-announcement-v1.git
```

- install repository
```bash
composer require thebachtiarz/laravel-announcement-v1
```

- register the REST API into -> **app/Providers/RouteServiceProvider.php**
```bash
Route::prefix(tbtoolkitconfig('app_prefix'))
    ->middleware(['api'])
    ->namespace($this->namespace)
    ->group(tbannrouteapi());
```

-------
### Feature

> sek males nulis cak :v
-------
