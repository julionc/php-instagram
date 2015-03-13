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

require_once 'TestCase.php';

class UserTest extends TestCase
{
    public function testUserInfo()
    {
        $this->mock([
            $this->dataResponse('user/self')
        ]);

        $response = $this->instagram->user()->info();

        $this->assertInternalType('object', $response);
        $this->assertEquals('snoopdogg', $response->username);
        $this->assertEquals('Snoop Dogg', $response->full_name);
    }

    public function testUserFeed()
    {
        $this->mock([
            $this->dataResponse('user/feed')
        ]);

        $response = $this->instagram->user()->feed();

        $image = $response[0]; // First item

        $this->assertInternalType('array', $response);
        $this->assertEquals('image', $image->type);
        $this->assertEquals('http://instagr.am/p/BXsFz/', $image->link);
    }

    /*
    public function testUserMedia()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    */
}