Pubnub Driver for Laravel & Lumen
==============

Integrates the [Pubnub](https://github.com/pubnub/php) PHP library with Laravel and Lumen

### Installation

```php
composer require unoapp-dev/laravel-pubnub
```

#### Laravel 5

Add a ServiceProvider to your providers array in `config/app.php`:

```php
'providers' => [
    'Unoappdev\PubnubDriver\PubnubServiceProvider',
]
```

#### Lumen

For `Lumen` add the following in your bootstrap/app.php
```php
$app->register(Unoappdev\PubnubDriver\PubnubServiceProvider::class);
```

#### Configuration

Then in the `.env` file, add the following API keys:

```bash
PUBNUB_PUBLISH_KEY={YOUR_PUBNUB_PUBLISH_KEY}
PUBNUB_SUBSCRIBE_KEY={YOUR_PUBNUB_SUBSCRIBE_KEY}
```

Next in your `config/broadcasting.php` file, under the `connections` array, add the PubNub settings:

```php
'pubnub' => [
    'driver' => 'pubnub',
    'publish_key' => env('PUBNUB_PUBLISH_KEY'),
    'subscribe_key' => env('PUBNUB_SUBSCRIBE_KEY'),
],
```

You probably want to change the default broadcast driver to `pubnub`.

