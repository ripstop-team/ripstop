<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\Application;
use Ripstop\Upload;

class Applications
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function get($appId): Application
    {
        $response = $this->api->applications->getById($appId);

        return Application::fromAPIResponse($response);
    }

    public function upload($appId, $filename, $dir): Upload
    {
        $response = $this->api->applications->uploads()->create($appId, $filename, $dir);

        return Upload::fromAPIResponse($response);
    }
}
