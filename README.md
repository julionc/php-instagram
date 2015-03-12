# php-instagram

A PHP library for the Instagram API

## Table of Contents

+ [Installation](#installation)
+ [How to Use](#how-to-use)
+ [EndPoints](#endpoints)
  + [User](#user)

## Installation

Using [composer](https://packagist.org/packages/julionc/instagram):

```
{
    "require": {
        "julionc/instagram": "*"
    }
}
```
## How to Use

```php
require_once('vendor/autoload.php');

$config = array(
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri' => 'CALLBACK_URL',
    'scope' => array('basic')
);

$client = new Instagram\Auth($config);

// In view, get the Authorize URL

$client->authorize_url();
```

```php
// profile.php
// Preload the settings and capture the access code (Callback step).

if (!$access_token) {
    $client = new Instagram\Auth($config);
    $client->requestAccessToken($access_code);
    $_SESSION['access_token'] = $client->getAccessToken();
}

$instagram = new \Instagram\Instagram($access_token);

```

If you do not wish to put your client credentials in your code (understandable), simply set it to the environment variable `instagram.client_id` and `instagram.client_secrect`.
So php-instagram will automatically pick it up.

# EndPoints

## User

```php
$instagram = new \Instagram\Instagram($access_token);

// Get basic information about a user.
$user = $instagram->user->info();

// See the authenticated user's feed.
$feed = $instagram->user->feed();

// Get the most recent media published by a user.

$media = $instagram->user->media();

```