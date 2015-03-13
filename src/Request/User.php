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

/**
 * User
 * @package Instagram\Request
 */
class User extends Request
{
    public function info()
    {
        $request['url'] = sprintf(
            'users/%s/?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($this->request, $request);
    }

    public function feed()
    {
        $request['url'] = sprintf(
            'users/self/feed?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($this->request, $request);
    }

    // Relationships

    public function follows()
    {
        $request['url'] = sprintf(
            'users/%s/follows?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($this->request, $request);
    }

    public function followers()
    {
        $request['url'] = sprintf(
            'users/%s/followed-by?access_token=%s',
            $this->getUser(),
            $this->getToken()
        );

        return array_merge($this->request, $request);
    }

    public function follow($user_id)
    {
        return $this->_relationship($user_id, 'follow');
    }

    public function unfollow($user_id)
    {
        return $this->_relationship($user_id, 'unfollow');
    }

    private function _relationship($user_id, $action)
    {
        $request = [];
        $request['method'] = 'POST';
        $request['url'] = sprintf(
            'users/%s/relationship?access_token=%s',
            $user_id,
            $this->getToken()
        );

        $body = ['action' => $action];
        $request['options'] = [
            'body' => $body
        ];

        return array_merge($this->request, $request);
    }
}