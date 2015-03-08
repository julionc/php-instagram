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
    protected $auth_url;

    public function setUp()
    {
        $this->auth_url = $this->buildUrl();
    }

    public function testAuthWithConfigArray()
    {
        $client = new Instagram\Auth($this->getConfig());

        $this->assertEquals($client->authorize_url(), $this->auth_url);
    }

    public function testAuthWithEnvironmentVariables()
    {
        putenv('instagram.client_id=YOUR_CLIENT_ID');
        putenv('instagram.client_secrect=YOUR_CLIENT_SECRET');
        putenv('instagram.redirect_uri=CALLBACK_URL');

        $client = new Instagram\Auth();

        $this->assertEquals($client->authorize_url(), $this->auth_url);
    }

    public function testAuthWithMoreScope()
    {
        $config = $this->getConfig();
        $config['scope'] = array('basic', 'comments', 'relationships', 'likes');

        $client = new Instagram\Auth($config);
        $auth_url = $this->buildUrl($config);

        $this->assertEquals($client->authorize_url(), $auth_url);
    }

    private function getConfig()
    {
        return array(
            'client_id' => 'YOUR_CLIENT_ID',
            'client_secret' => 'YOUR_CLIENT_SECRET',
            'redirect_uri' => 'CALLBACK_URL',

        );
    }

    private function buildUrl(array $data = [])
    {
        $_default = array(
            'client_id' => 'YOUR_CLIENT_ID',
            'redirect_uri' => 'CALLBACK_URL',
            'scope' => 'basic',
            'response_type' => 'code'
        );

        if (empty($data)) {
            $data = $_default;
        } else {

            $_scope = $data['scope'];
            if (is_array($_scope)) {
                $scope = implode(' ', $data['scope']);
                $data['scope'] = $scope;
            }

            $data = array_replace($_default, $data);
        }

        unset($data['client_secret']);
        $url = 'https://api.instagram.com/oauth/authorize/?';

        return $url . http_build_query($data);
    }
}