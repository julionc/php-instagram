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
 *
 * @method $this info()
 * @method $this feed()
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
     * @return \Instagram\Instagram
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

        $response = $this->sendRequest($request);
        $data = $response['data'];
        $this->user = '';

        return json_decode(json_encode($data), false);
    }

    protected function sendRequest($data)
    {
        $method = $data['method'];
        $url = $this->api->getBaseUrl() . $data['url'];
        $options = $data['options'] ?: [];

        $request = $this->api->createRequest($method, $url, $options);
        $response = $this->api->send($request);

        return $response->json();
    }

    public function __call($method, $arguments)
    {
        $request_class = 'Instagram\Request\User' . ucwords($method);
        array_unshift($arguments, $this->user, $this->getToken());

        if (class_exists($request_class)) {
            $class = new \ReflectionClass($request_class);
            $request = $class->newInstanceArgs($arguments);

            return $this->send($request->request());
        }
        throw new \Exception('Unknown method "' . $method . '"');
    }

}