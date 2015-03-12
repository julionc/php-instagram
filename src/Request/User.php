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

class User
{
    protected $user;
    protected $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function info()
    {
        $request['method'] = 'GET';
        $request['url'] = sprintf(
            '/users/%s/?access_token=%s',
            $this->user,
            $this->token
        );

        return $request;
    }

    public function feed()
    {
        $request['method'] = 'GET';
        $request['url'] = sprintf(
            '/users/self/feed?access_token=%s',
            $this->user,
            $this->token
        );

        return $request;
    }
}