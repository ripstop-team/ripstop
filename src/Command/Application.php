<?php

namespace Ripstop\Command;

use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Robo\Robo;
use Robo\Tasks;

class Application extends Tasks
{
    public function appGetidforname(string $slug)
    {
        try {
            /** @var \Ripstop\Application $application */
            $application = Robo::service('applicationForName')($slug);
            $this->say($application->getId());
        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }

    public function appUpload(int $appId, string $dir, string $filename)
    {
        try {
            // Get all users
            $application = Robo::service('applications');
            $application->upload($appId, $dir, $filename);

        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }
}
