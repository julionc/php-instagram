<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instagram\Request;

class UserFeed extends Request
{
    public function __construct($user, $token)
    {
        $this->url = sprintf(
            '/users/self/feed?access_token=%s',
            $token
        );
    }
}