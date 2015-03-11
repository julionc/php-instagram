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

if (!$access_token) {
    $config = require_once 'config.php';
    $client = new Instagram\Auth($config);
    $client->requestAccessToken($access_code);
    $_SESSION['access_token'] = $client->getAccessToken();
}

$instagram = new \Instagram\Instagram($access_token);

$user = $instagram->user()->info();

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
<pre>
    <?php print_r($user); ?>
</pre>
<hr/>

</body>
</html>
