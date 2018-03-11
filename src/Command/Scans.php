<?php

namespace Ripstop\Command;

use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Robo\Robo;
use Robo\Tasks;

class Scans extends Tasks
{
    public function scansList(int $appId, int $limit = 1)
    {
        try {
            // Get all users
            $scans = Robo::service('scans')($appId, $limit);
            /** @var \Ripstop\Scan $scan */
            foreach ($scans as $scan) {
                $this->say($scan->getId());
            }
        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }
}
