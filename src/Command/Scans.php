<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Command;

use RIPS\Connector\Exceptions\ClientException;
use RIPS\Connector\Exceptions\ServerException;
use Robo\Robo;
use Robo\Tasks;

class Scans extends Tasks
{
    public function scansList($application, int $limit = 1)
    {
        /** @var \Ripstop\Application $application */
        $application = Robo::service('applications')->get($application);

        try {
            // Get all users
            $scans = Robo::service('scans')->latest($application->getId(), $limit);
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

    public function scansCreate($application, string $filepath, string $version)
    {
        try {
            /** @var \Ripstop\Service\Applications $uploadService */
            $applicationService = Robo::service('applications');
            /** @var \Ripstop\Application $application */
            $application = $applicationService->get($application);
            $upload      = $applicationService->upload($application->getId(), basename($filepath), $filepath);

            /** @var \Ripstop\Service\Scans $scans */
            $scans  = Robo::service('scans');
            $result = $scans->create($application, $upload, $version);
            $this->say(sprintf('Scan %1$s successfuly created', $result->getId()));
        } catch (ClientException $e) {
            $this->io()->error($e->getMessage());
        } catch (ServerException $e) {
            $this->io()->error($e->getMessage());
        }
    }
}
