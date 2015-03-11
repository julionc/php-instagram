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

$config_file = __DIR__ . '/config.php';

if (!file_exists($config_file)) {
    die("[Error] You have to edit your own config file.");
}

$config = require_once __DIR__ . '/config.php';
$client = new Instagram\Auth($config);

?>

<a href="<?php echo $client->authorize_url(); ?>">Log in</a>