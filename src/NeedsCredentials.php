<?php namespace Ripstop;

use Robo\Robo;

trait NeedsCredentials
{

    protected function getCredentials(): Credentials
    {
        $credentials = Robo::service('credentials');

        return $credentials();
    }
}
