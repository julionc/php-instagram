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
     * The authorization url for the Instagram API
     *
     * @var string $auth_url
     */
    protected $auth_url;

    /**
     * The Client Id
     * @var string
     */
    protected $client_id;

    /**
     * The Client Secret
     * @var string
     */
    protected $client_secret;

    /**
     * The Redirect URI
     * @var string
     */
    protected $redirect_uri;

    /**
     * Scope
     * @var string
     */
    protected $scope;

    /**
     * The Construct
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->client_id = $config['client_id'] ?: getenv('instagram.client_id');
        $this->client_secret = $config['client_secret'] ?: getenv('instagram.client_secret');
        $this->redirect_uri = $config['redirect_uri'] ?: getenv('instagram.redirect_uri');
        $this->scope = $config['scope'] ?: array('basic');
    }

    /**
     * The Authorize Url
     *
     * @return string The Instagram Oauth Url
     */
    public function authorize_url()
    {
        $_scope = $this->scope;

        $data = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
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
     * Generate OAuth Token
     *
     * @param string $code The Access code
     * @return string
     * @throws \Exception
     */
    public function generateOAuthToken($code)
    {
        if (!$code) {
            throw new \Exception('Your Instagram Access code is not set.');
        }

        $body = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
            'code' => $code
        ];

        $options = [
            'body' => $body,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ];

        $client = new Client();
        $response = $client->post('https://api.instagram.com/oauth/access_token', $options);
        $obj = $response->json();

        return $obj['access_token'];
    }

}