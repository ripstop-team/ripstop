<?php namespace Ripstop\Service;

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
        $scan = Robo::service('scans')->get($appId, $scanId);

        return [
            'application_slug'    => $application->getName(),
            'application_version' => $scan->getVersion(),
        ];
    }
}
