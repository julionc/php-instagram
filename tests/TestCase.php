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
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock;
use Instagram;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $instagram;
    protected $client;
    protected $history;

    public function setUp()
    {
        $reflected = new \ReflectionClass('Instagram\Instagram');

        $this->history = new History();

        $this->instagram = new Instagram\Instagram('random', 'secret_key');

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

    protected function getFixtureData($filename)
    {
        $file = __DIR__ . '/fixtures/' . $filename . '.json';
        if (!file_exists($file)) {
            throw new \Exception('You must specify a correct Fixture file name.');
        }

        return file_get_contents($file);
    }

    protected function dataResponse($filename)
    {
        $body = $this->getFixtureData($filename);
        $response = [
            'HTTP/1.1 200 OK',
            'Content-Length: ' . strlen($body),
            '',
            $body,
        ];

        return implode("\r\n", $response);
    }
}
