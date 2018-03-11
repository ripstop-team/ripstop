<?php namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\Application;

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
}
