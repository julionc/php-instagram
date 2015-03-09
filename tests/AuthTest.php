<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Instagram;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthWithConfigArray()
    {
        $client = new Instagram\Auth($this->getDefaultConfig());
        $this->assertEquals($client->authorize_url(), $this->getDefaultAuthorizeUrl());
    }

    public function testAuthWithEnvironmentVariables()
    {
        putenv('instagram.client_id=YOUR_CLIENT_ID');
        putenv('instagram.client_secrect=YOUR_CLIENT_SECRET');
        $config = $this->getDefaultConfig();
        unset($config['client_id']);
        unset($config['secret_id']);

        $client = new Instagram\Auth($config);
        $this->assertEquals($client->authorize_url(), $this->getDefaultAuthorizeUrl());
    }

    public function testAuthWithMoreScopes()
    {
        $config = $this->getDefaultConfig();
        $config['scope'] = array('basic', 'comments', 'relationships', 'likes');

        $client = new Instagram\Auth($config);
        $this->assertEquals($client->authorize_url(), $this->getDefaultAuthorizeUrl($config['scope']));
    }

    public function testRequestAccessToken()
    {
        $client = $this->getMockBuilder('\Instagram\Auth')
            ->setMethods(array('__construct'))
            ->setConstructorArgs($this->getDefaultConfig())
            ->disableOriginalConstructor()
            ->getMock();

        $client->requestAccessToken = 'fb2e77d.47a0479900504cb3ab4a1f626d174d2d';
        $token = $client->requestAccessToken;

        $mock = $this->getMockResponse('dump');
        $obj = json_decode($mock);

        $this->assertEquals($token, $obj->access_token);

    }

    private function getDefaultConfig()
    {
        return array(
            'client_id' => 'YOUR_CLIENT_ID',
            'client_secret' => 'YOUR_CLIENT_SECRET',
            'redirect_uri' => 'CALLBACK_URL',
            'scope' => array('basic')
        );
    }

    private function getDefaultAuthorizeUrl($scope = 'basic')
    {
        if (is_array($scope)) {
            $scope = implode("+", $scope);
        }

        $result = 'https://api.instagram.com/oauth/authorize/';
        $result .= '?client_id=YOUR_CLIENT_ID';
        $result .= '&redirect_uri=CALLBACK_URL';
        $result .= '&scope=' . $scope;
        $result .= '&response_type=code';

        return $result;
    }

    protected function getMockResponse($file_name)
    {
        $client = new Client();

        $mock = new Mock();
        $mock->addResponse(__DIR__ . '/mocks/' . $file_name . '.txt');

        $client->getEmitter()->attach($mock);
        $response = $client->get();

        return $response->getBody()->getContents();
    }
}