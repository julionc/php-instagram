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
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\History;

require_once 'TestCase.php';

class AccessTokenTest extends TestCase
{
    public function setUp()
    {
        $reflected = new \ReflectionClass('Instagram\Auth');

        $this->history = new History();

        $config = $this->getDefaultConfig();

        $this->instagram = new Instagram\Auth($config);

        $this->client = $reflected->getProperty('api');
        $this->client->setAccessible(true);
        $this->client->setValue($this->instagram, new Client());
        $this->client->getValue($this->instagram)->getEmitter()->attach($this->history);
    }

    public function testRequestAccessToken()
    {
        $this->mock([
            $this->dataResponse('oauth')
        ]);

        $client = $this->instagram;
        $client->requestAccessToken('random');

        $this->assertInternalType('object', $client);
        $this->assertEquals('fb2e77d.47a0479900504cb3ab4a1f626d174d2d', $client->getAccessToken());
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
}