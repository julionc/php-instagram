# php-instagram

A PHP library for the Instagram API

## Table of Contents

+ [Installation](#installation)
+ [Get Oauth Access Token](#get-oauth-access-token)

## Installation

Using [composer](https://packagist.org/packages/julionc/instagram):

```
{
    "require": {
        "julionc/instagram": "*"
    }
}
```

```php
require_once('vendor/autoload.php');

$config = array(
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri' => 'CALLBACK_URL',
    'scope' => array('basic')
);

$client = new Instagram\Auth($config);

// Get the Authorize Url
// $client->authorize_url();
```


```php
// profile.php

$client = new Instagram\Auth($config);
$client->requestAccessToken($access_code);

if (!$access_token) {
    $config = require_once 'config.php';
    $client = new Instagram\Auth($config);
    $client->requestAccessToken($access_code);
    $_SESSION['access_token'] = $client->getAccessToken();
}

$instagram = new \Instagram\Instagram($access_token);

$user = $instagram->user()->info();

```

If you do not wish to put your access token in your code (understandable), simply set it to the environment variable `instagram.client_id` and `instagram.client_secrect`. So php-instagram will automatically pick it up.
