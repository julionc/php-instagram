<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

session_start();
$access_code = $_REQUEST['code'] ?: null;

if ($access_code) {
    header('Location: profile.php');
    $_SESSION['access_code'] = $access_code;
} else {
    header('Location: index.php');
}