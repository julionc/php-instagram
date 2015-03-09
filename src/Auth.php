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
use GuzzleHttp\Post\PostBody;

/**
 * Instagram Auth
 * @package Instagram
 */
class Auth
{
    /**
     * Config
     * @var array
     */
    protected $config = array();

    /**
     * Token
     * @var string
     */
    protected $token;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // Env variables
        if (!array_key_exists('client_id', $config)) {
            $config['client_id'] = getenv('instagram.client_id');
        }

        if (!array_key_exists('client_secret', $config)) {
            $config['client_secret'] = getenv('instagram.client_secret');
        }

        $this->config = $config;
    }

    /**
     * The Authorize Url
     *
     * @return string The Instagram Oauth Url
     */
    public function authorize_url()
    {
        $_scope = $this->config['scope'];

        $data = array(
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => reset($_scope),
            'response_type' => 'code'
        );

        if (count($_scope) !== 1) {
            $data['scope'] = implode(" ", $_scope);
        }

        $url = 'https://api.instagram.com/oauth/authorize/?' . http_build_query($data);

        return $url;
    }

    /**
     * Request Access Token
     *
     * @param string $code The Access code
     * @return string
     * @throws \Exception
     */
    public function requestAccessToken($code)
    {
        if (!$code) {
            throw new \Exception('Your Instagram Access code is not set.');
        }

        $body = [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->config['redirect_uri'],
            'code' => $code
        ];

        $options = [
            'body' => $body,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ];

        $client = new Client();
        $response = $client->post('https://api.instagram.com/oauth/access_token', $options);

        $data = json_decode($response->getBody());

        if (empty($data)) {
            throw new \RuntimeException('Unable to parse access token JSON data from Instagram API');
        }

        $this->token = $data->access_token;

        return $data;
    }

    /**
     * Retrieve Access Token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->token;
    }

}