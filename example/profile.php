<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once '../vendor/autoload.php';

session_start();

$access_code = $_SESSION['access_code'] ?: null;
$access_token = $_SESSION['access_token'] ?: null;

if (!$access_code) {
    header('Location: index.php');
}

$config = require_once 'config.php';

if (!$access_token) {
    $client = new Instagram\Auth($config);
    $client->requestAccessToken($access_code);
    $_SESSION['access_token'] = $client->getAccessToken();
}

$instagram = new \Instagram\Instagram($access_token, $config['client_secret']);

$user = $instagram->user()->info();

$follows = $instagram->user()->follows();
$followers = $instagram->user()->followers();

?>
<html>
<head>

</head>
<body>
<h1>php-instagram</h1>
<table border="0">
    <tr>
        <td><strong>Access Code:</strong></td>
        <td><?php echo $access_code; ?></td>
    </tr>
    <tr>
        <td><strong>Access Token:</strong></td>
        <td><?php echo $access_token; ?></td>
    </tr>
</table>
<hr/>
<h3>Info</h3>
<pre>
    <?php print_r($user); ?>
</pre>
<hr/>
<h3>Follows</h3>
<pre>
    <?php print_r($follows); ?>
</pre>
<hr/>
<h3>Followers</h3>
<pre>
    <?php print_r($followers); ?>
</pre>

</body>
</html>
