<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instagram\Resources;

/**
 * Class Request
 * @package Instagram\Request
 */
abstract class Request
{
    /**
     * @var array $options The options
     */
    protected $options;

    /**
     * @var array $request The request data and options
     */
    protected $request = [
        'method' => 'GET',
        'url' => '',
        'options' => [],
    ];

    /**
     * Construct
     *
     * @param string $user The Instagram User
     * @param string $token The Instagram Access Token
     */
    public function __construct($user, $token)
    {
        $this->options['user'] = $user;
        $this->options['token'] = $token;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->options['user'];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->options['token'];
    }
}
