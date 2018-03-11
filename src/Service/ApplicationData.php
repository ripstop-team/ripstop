<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\Application;
use Ripstop\Scan;
use Robo\Robo;

class ApplicationData
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function __invoke($appId, $scanId): array
    {
        /** @var Application $application */
        $application = Robo::service('applications')->get($appId);
        /** @var Scan $scan */
        $scan = Robo::service('scans')->get($application, $scanId);

        return [
            'application_slug'    => $application->getName(),
            'application_version' => $scan->getVersion(),
        ];
    }
}
