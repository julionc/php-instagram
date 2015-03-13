# php-instagram

[![Latest Version](https://img.shields.io/github/release/julionc/php-instagram.svg?style=flat)](https://github.com/julionc/php-instagram/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/julionc/php-instagram/master.svg?style=flat)](https://travis-ci.org/julionc/php-instagram)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/julionc/php-instagram.svg?style=flat)](https://scrutinizer-ci.com/g/julionc/php-instagram/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/julionc/php-instagram.svg?style=flat)](https://scrutinizer-ci.com/g/julionc/php-instagram)
[![Total Downloads](https://img.shields.io/packagist/dt/julionc/php-instagram.svg?style=flat)](https://packagist.org/packages/julionc/php-instagram)

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
        "julionc/instagram": "dev-master"
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

$instagram = new \Instagram\Instagram($access_token, 'secret_key_here');

```

If you do not wish to put your client credentials in your code (understandable), simply set it to the environment variable `instagram.client_id` and `instagram.client_secrect`.
So php-instagram will automatically pick it up.
See `example` folder.

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