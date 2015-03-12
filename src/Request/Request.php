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

abstract class Request
{
    protected $options;
    protected $request = [
        'method' => 'GET',
        'options' => [],
    ];

    public function __construct($user, $token)
    {
        $this->options['user'] = $user;
        $this->options['token'] = $token;
    }

    public function getUser()
    {
        return $this->options['user'];
    }

    public function getToken()
    {
        return $this->options['token'];
    }
}