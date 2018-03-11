<?php namespace Ripstop;

class Application
{
    public static function fromAPIResponse($response): self
    {
        $application     = new self();
        $application->id = $response->id;

        return $application;
    }
}
