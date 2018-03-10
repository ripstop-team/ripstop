<?php namespace Ripstop\Service;

use Robo\Config\Config;
use Robo\Robo;
use Ripstop\Credentials as CredentialsObject;

class Credentials
{
    public function get(): CredentialsObject
    {
        /** @var Config $config */
        $config   = Robo::service('config');
        $username = $config->get('credentials.username');
        $password = $config->get('credentials.password');

        return new CredentialsObject($username, $password);
    }
}
