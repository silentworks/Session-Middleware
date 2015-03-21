# Session-Middleware

Slim Framework session middleware

### How to use

```php
$app = new Slim\App();
$app->register(new Slim\Session\SessionServiceProvider());
$app->add(new Slim\Session\SessionMiddleware($app['session']));
```

You can also access the session from inside of your routes:

```php
$app->get('/', function () {
    $this['session']->set('name', 'Slim Framework');
})->setName('home');

$app->get('/about', function () {
    echo $this['session']->get('name');
})->setName('about');
```