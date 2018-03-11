<?php namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\ScanCollection;
use Ripstop\Scan;

class Scans
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function get($appId, $scanId): Scan
    {
        $response = $this->api->applications->scans()->getById($appId, $scanId);

        return Scan::fromAPIResponse($response);
    }

    public function latest($appId, $limit = 1): ScanCollection
    {
        $response = $this->api->applications->scans()->getAll($appId);
        $scans    = ScanCollection::fromAPIResponse($response)
                                  ->limit($limit);

        return $scans;
    }
}
