<?php

namespace Ripstop\Command;

use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Robo\Robo;
use Robo\Tasks;
use Ripstop\ApplicationCollection;

class Application extends Tasks
{
    public function appGetidforname(string $slug)
    {
        try {
            // Get all users
            $this->say(Robo::service('applicationIdForName')($slug)->id);
        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }
}
