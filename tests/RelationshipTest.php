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

class RelationshipTest extends TestCase
{
    public function testUserFollows()
    {
        $this->mock([
            $this->dataResponse('relationships/follows')
        ]);

        $response = $this->instagram->user()->follows();

        $user0 = $response[0];
        $user1 = $response[1];

        $this->assertInternalType('array', $response);
        $this->assertEquals('kevin', $user0->username);
        $this->assertEquals('instagram', $user1->username);
    }

    public function testUserCanFollow()
    {
        $this->mock([
            $this->dataResponse('relationships/relationship')
        ]);

        $response = $this->instagram->user()->follow('kevin');

        $this->assertInternalType('object', $response);
        $this->assertEquals('requested', $response->outgoing_status);
    }

    public function testUserCanUnFollow()
    {
        $this->mock([
            $this->dataResponse('relationships/relationship')
        ]);

        $response = $this->instagram->user()->unfollow('instagram');

        $this->assertInternalType('object', $response);
        $this->assertEquals('requested', $response->outgoing_status);
    }
}