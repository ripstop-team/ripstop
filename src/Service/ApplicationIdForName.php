<?php

namespace Ripstop\Service;

use RIPS\Connector\API;
use Ripstop\Application;
use Ripstop\ApplicationCollection;

class ApplicationIdForName
{
    private $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function __invoke(string $slug): Application
    {
        try {
            $response = $this->api
                ->applications->getAll([
                    'equal[name]' => $slug
                ]);
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            throw new \Exception(sprintf(
                'There was a problem finding the application %1$s',
                $slug
            ));
        }

        $apps = ApplicationCollection::fromAPIResponse($response)
                                     ->limit(1);
        if ($apps->count() < 1) {
            throw new \Exception(sprintf(
                'There seems to be no application %1$s',
                $slug
            ));
        }

        return $apps[0];
    }
}
