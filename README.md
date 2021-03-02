<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Recreation of Pub Sub System using Http Request

Usage

Run composer install

setup the enronment variable using .env.example

Generate application key php artisan key:generate
Run migrations php artisan migrate

Start the server: php artisan serve
Start the websocket engine using php artisan websockets:serve

+++++++++++++++++++++++ SUBCRIPTION
-POST /api/subscribe/{TOPIC}
-BODY { "url": "http://localhost:8000/event"}


+++++++++++++++++++++++PUBLISH
-POST /api/publish/{TOPIC}
-BODY { "message": "hello"}

+++++++++++++++++++++++EVENT
-GET /api/event
-optional passing in url params
- /api/event?url=http://localhost:8000/event
