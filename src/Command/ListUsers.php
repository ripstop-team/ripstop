<?php namespace Ripstop\Command;

use RIPS\Connector\API;
use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Robo\Tasks;

class ListUsers extends Tasks
{
    const BASE_URI = 'https://api-2.ripstech.com';

    public function showUsers($opts = ['username|u' => 'testuser', 'password|p' => 'testpassword'])
    {
        $config = ['base_uri' => self::BASE_URI];

        // Initialize with config in constructor
        $api = new API($opts['username'], $opts['password'], $config);

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
