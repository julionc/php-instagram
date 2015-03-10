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
    protected $method = 'GET';
    protected $url;
    protected $options = [];

    public function request()
    {
        $request = [
            'method' => $this->method,
            'url' => $this->url
        ];

        return array_merge($request, $this->options);
    }
}