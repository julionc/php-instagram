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

/**
 * Instagram
 * @package Instagram
 *
 */
class Instagram
{
    /**
     * An instance of the Guzzle client
     *
     * @var \GuzzleHttp\Client $api
     */
    protected $api;

    protected $user;
    protected $token;
    protected $client_secret;

    public function __construct($access_token, $client_secret = null)
    {
        $client_secret = getenv('instagram.client_secret') ?: $client_secret;

        if (!$access_token) {
            throw new \Exception('Your Instagram Access Token is not set.');
        }

        if (!$client_secret) {
            throw new \Exception('Your Instagram Client Secret key is not set.');
        }

        $this->token = $access_token;
        $this->client_secret = $client_secret;
        $this->api = (new Connection($access_token))->client();
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * Set the passed in user
     *
     * @return \Instagram\Resources\User
     */
    public function user()
    {
        $this->user = func_get_args() ? reset(func_get_args()) : 'self';

        return $this;
    }

    protected function send($request)
    {
        if (empty($this->user)) {
            throw new \Exception('You must specify the user to send API calls.');
        }

        $method = $request['method'];
        $url = $this->api->getBaseUrl() . $request['url'];
        $options = isset($request['options']) ? $request['options'] : [];

        if ('POST' === $method) {
            $signed_header = $this->getSignedHeader();
            $options['headers'] = [
                'X-Insta-Forwarded-For' => $signed_header
            ];
        }

        $response = $this->sendRequest($method, $url, $options);
        $data = $response['data'];
        $this->user = null;

        return json_decode(json_encode($data), false);
    }

    protected function sendRequest($method, $url, array $options = [])
    {
        $request = $this->api->createRequest($method, $url, $options);
        $response = $this->api->send($request);

        return $response->json();
    }

    protected function getSignedHeader()
    {
        $ips = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
        $secret = $this->getClientSecret();

        $signature = (hash_hmac('sha256', $ips, $secret, false));
        $header = join('|', array($ips, $signature));

        return $header;
    }

    public function __call($method, $arguments)
    {
        if ($this->user) {

            $defaults = [$this->user, $this->getToken()];

            $class = new \ReflectionClass('Instagram\Resources\User');
            $object = $class->newInstanceArgs($defaults);

            if (method_exists($object, $method)) {
                $reflect_method = new \ReflectionMethod($object, $method);
                $request = $reflect_method->invokeArgs($object, $arguments);

                return $this->send($request);
            }
            throw new \Exception('Unknown method "' . $method . '"');
        }
    }

}
