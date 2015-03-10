<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instagram;

use GuzzleHttp\Client;

/**
 * Instagram
 * @package Instagram
 */
class Connection
{
    /**
     * The base url for the Instagram API
     *
     * @var string $base_url
     */
    protected $base_url = 'https://api.instagram.com/{version}/';

    /**
     * The version of the Instagram API
     *
     * @var string $version
     */
    protected $version = 'v1';
    protected $client;

    /**
     * Get the Guzzle client with defaults
     *
     * @return \GuzzleHttp\Client
     */
    public function __construct($access_token)
    {
        $this->client = new Client([
            'base_url' => [
                $this->base_url,
                ['version' => $this->version],
            ],
            'defaults' => [
                'auth' => [$access_token, ''],
            ],
        ]);
    }

    public function client()
    {
        return $this->client;
    }
}