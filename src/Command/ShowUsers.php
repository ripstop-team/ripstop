<?php

namespace Ripstop\Commands;

use RIPS\Connector\API;
use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Ripstop\NeedsCredentials;
use Robo\Tasks;

class ShowUsers extends Tasks
{
    use NeedsCredentials;

    const BASE_URI = 'https://api-2.ripstech.com';

    public function showUsers()
    {
        $config      = ['base_uri' => self::BASE_URI];
        $credentials = $this->getCredentials();

        // Initialize with config in constructor
        $api = new API($credentials->username(), $credentials->password(), $config);

        try {
            // Get all users
            $users = $api->users->getAll();
            foreach ($users as $user) {
                $this->say("[ {$user->id} ] {$user->username}");
            }
        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }
}
