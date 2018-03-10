<?php namespace Ripstop;

trait NeedsCredentials
{

    private function getCredentials(): Credentials
    {
        // TODO: Should fetch credentials from the container.
        return new Credentials('testuser', 'testpassword');
    }
}
