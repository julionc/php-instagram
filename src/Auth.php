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
     * The Instagram Base URL
     *
     * @var string $base_url
     */
    private $base_url = 'https://api.instagram.com';

    /**
     * The Instagram Credentials
     *
     * @var array $config
     */
    private $config = array();

    /**
     * Instagram Access Token
     * @var string
     */
    private $token;

    /**
     * An instance of the Guzzle client
     *
     * @var \GuzzleHttp\Client $api
     */
    protected $api;

    /**
     * Constructor
     *
     * @param array $config The Instagram Credentials
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
        $this->api = (new Connection())->client();
    }

    /**
     * The Authorize Url
     *
     * @return string The Instagram OAuth Url
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

        $url = $this->base_url . '/oauth/authorize/?' . http_build_query($data);

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
            throw new \Exception('Your Instagram Access Code is not set.');
        }

        $body = [
            'client_id' => $this->getConfigParameter('client_id'),
            'client_secret' => $this->getConfigParameter('client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getConfigParameter('redirect_uri'),
            'code' => $code
        ];

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'body' => $body
        ];

        $url = $this->base_url . '/oauth/access_token';

        $response = $this->api->post($url, $options);
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

    /**
     * Retrieve a config parameter
     *
     * @param string $key The key
     * @return mixed The value of the given key
     * @throws \Exception
     */
    public function getConfigParameter($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        throw new \Exception('The ' . $key . ' parameter key does not exist');
    }
}