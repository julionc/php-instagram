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

    /**
     * An instance of the Guzzle client
     *
     * @var \GuzzleHttp\Client $client
     */
    protected $client;

    /**
     * Constructor
     * @param string $access_token The Instagram Access token
     */
    public function __construct($access_token = '')
    {
        $this->client = new Client([
            'base_url' => [
                $this->base_url,
                ['version' => $this->version],
            ],
            'defaults' => [
                'auth' => [$access_token, ''],
            ],
            'headers' => [
                'User-Agent' => $this->getDefaultUserAgent()
            ]
        ]);
    }

    /**
     * Get the Guzzle client with defaults
     *
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Get the default User-Agent string to use with Guzzle
     *
     * @return string
     */
    public static function getDefaultUserAgent()
    {
        static $defaultAgent = '';
        if (!$defaultAgent) {
            $defaultAgent = 'php-instagram';
            if (extension_loaded('curl')) {
                $defaultAgent .= ' curl/' . curl_version()['version'];
            }
            $defaultAgent .= ' PHP/' . PHP_VERSION;
        }

        return $defaultAgent;
    }
}