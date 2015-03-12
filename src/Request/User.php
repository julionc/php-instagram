<?php

/*
 * This file is part of the Instagram package.
 *
 * (c) Julio Napurí <julionc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Instagram\Request;

class User extends Request
{
    public function info()
    {
        $request['url'] = sprintf(
            '/users/%s/?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($request, $this->request);
    }

    public function feed()
    {
        $request['url'] = sprintf(
            '/users/self/feed?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($request, $this->request);
    }

}