<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Service;

use RIPS\Connector\API;

class Reports
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function __invoke($appId, $scanId, $filename): bool
    {
        try {
            $this->api
                ->applications
                ->scans()
                ->exports()
                ->exportPdf($appId, $scanId, $filename);

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
