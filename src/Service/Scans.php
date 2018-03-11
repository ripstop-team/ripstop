<?php namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\ScanCollection;
use Ripstop\Scan;
use Ripstop\Application;
use Ripstop\Upload;

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

    public function create(
        Application $app,
        Upload $upload,
        string $version,
        array $callbacks = []
    ) : Scan
    {
        /** @var \RIPS\Connector\Requests\Application\ScanRequests $scans */
        $scans = $this->api->applications->scans();

        $parameters = [
                "codeStored" => true,
                "uploadRemoved" => false,
                "fullCodeCompared" => true,
                "historyInherited" => true,
                "issueTypes" => [10],
                "version" => $version,
                "upload" => $upload->getId(),
                "analysisDepth" => 5,
                "callbacks" => $callbacks,
        ];


        $response = $scans->create($app->getId(), $parameters);

        return Scan::fromAPIResponse($response);
    }
}
