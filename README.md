# Laravel Pug



A small package that adds support for compiling Pug (Jade) templates to Laravel via [Pug.php](https://github.com/pug-php/pug).  Both vanilla php and [Blade syntax](http://laravel.com/docs/5.2/templates#blade-templating) is supported within the view.


## Installation

1. Run `composer require dts/laravel-pug`
2. Add the service provider to your app.php providers: `DTS\LaravelPug\ServiceProvider,`



## Configuration

All [Pug.php](https://github.com/pug-php/pug) config options are passed through via a Laravel config array.

* Publish the config file with `php artisan config:publish dts/laravel-pug  --path=vendor/dts/laravel-pug/config` and edit at /app/config/packages/dts/laravel-pug/config.php  



## Usage

Laravel Pug registers the ".pug", ".pug.php", ".pug.blade", and ".pug.blade.php" as well as the ".jade", ".jade.php", ".jade.blade", and ".jade.blade.php" extensions with Laravel and forwards compile requests on to Pug.php.  It compiles your Pug templates in the same way as Blade templates; the compiled template is put in your storage directory.  Thus, you don't suffer compile times on every page load.

In other words, just put your Pug files in the regular views directory and name them like "whatever.pug".  You reference them in Laravel like normal:

* `View::make('home.whatever')` for `app/views/home/whatever.pug`

The Pug view files can work side-by-side with regular PHP views.  To use Blade templating within your Pug, just name the files with ".pug.blade" or ".pug.blade.php" extensions.


## Extending Layouts / Include Sub-views

Default root directory for templates is `app/views`, so from any template any deep in the directory, you can use absolute paths to get other pug files from the root: `extends /layouts/main` will extends the file `resources/views/layouts/main.(pug|jade)`, `include /partial/foo/bar`, will include `resources/views/partial/foo/bar.(pug|jade)`. You can use the `basedir` option to set the root to an other directory. Paths that does not start with a slash will be resolved relatively to the current template file.


## Histoy

Read the Github [project releases](https://github.com/WClab/laravel-pug/releases) for release notes.
