<?php namespace Ripstop\Service;

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
