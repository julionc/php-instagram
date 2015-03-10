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
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $instagram;
    protected $client;
    protected $history;

    public function setUp()
    {
        $reflected = new \ReflectionClass('Instagram\Instagram');

        $this->history = new History();

        $this->instagram = new Instagram\Instagram('random');

        $this->client = $reflected->getProperty('api');
        $this->client->setAccessible(true);
        $this->client->setValue($this->instagram, new Client());
        $this->client->getValue($this->instagram)->getEmitter()->attach($this->history);
    }

    protected function mock($mock)
    {
        $mock = new Mock($mock);
        $this->client->getValue($this->instagram)->getEmitter()->attach($mock);
    }

    protected function dataResponse($file_name)
    {
        $body = $this->getFixtureData($file_name);
        $response = [
            'HTTP/1.1 200 OK',
            'Content-Length: ' . strlen($body),
            '',
            $body,
        ];

        return implode("\r\n", $response);
    }

    protected function getFixtureData($file_name)
    {
        $file = __DIR__ . '/fixtures/' . $file_name . '.json';
        if (!file_exists($file)) {
            throw new \Exception('You must specify a correct Fixture file name.');
        }

        return file_get_contents($file);
    }

    public function testUserInfo()
    {
        $this->mock([
            $this->dataResponse('self'),
        ]);

        $response = $this->instagram->user()->info();

        $this->assertInternalType('object', $response);
        $this->assertEquals('snoopdogg', $response->username);
        $this->assertEquals('Snoop Dogg', $response->full_name);
    }

}