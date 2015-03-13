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

use Instagram;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthWithConfigArray()
    {
        $config = $this->getDefaultConfig();
        $client = new Instagram\Auth($config);
        $this->assertEquals($client->authorize_url(), $this->getDefaultAuthorizeUrl());
    }

    public function testAuthWithEnvironmentVariables()
    {
        $config = $this->getDefaultConfig();
        unset($config['client_id']);
        unset($config['secret_id']);

        putenv('instagram.client_id=YOUR_CLIENT_ID');
        putenv('instagram.client_secrect=YOUR_CLIENT_SECRET');

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
}