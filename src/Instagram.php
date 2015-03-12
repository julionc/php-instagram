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

use Instagram\Request\User;

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

    protected $token;

    protected $user;


    public function __construct($access_token)
    {
        if (!$access_token) {
            throw new \Exception('Your Instagram Access Token is not set.');
        }
        $this->token = $access_token;
        $this->api = (new Connection($access_token))->client();
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the passed in user
     *
     * @return \Instagram\Request\User
     */
    public function user()
    {
        $this->user = func_get_args() ?: 'self';

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

    public function __call($method, $arguments)
    {
        if ($this->user) {

            $defaults = [$this->user, $this->getToken()];

            $class = new \ReflectionClass('Instagram\Request\User');
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