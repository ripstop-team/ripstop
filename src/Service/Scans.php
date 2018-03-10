<?php namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\ScanCollection;

class Scans
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function __invoke($appId, $limit = 1): ScanCollection
    {
        $response = $this->api->applications->scans()->getAll($appId);
        $scans    = ScanCollection::fromAPIResponse($response)
                                  ->limit($limit);

        return $scans;
    }
}
