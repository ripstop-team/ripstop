<?php namespace Ripstop;

class Scan
{
    public static function fromAPIResponse($response): Scan
    {
        $scan     = new self();
        $scan->id = $response->id;

        return $scan;
    }
}
