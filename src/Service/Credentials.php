<?php namespace Ripstop\Service;

use Robo\Config\Config;
use Robo\Robo;
use Ripstop\Credentials as CredentialsObject;

class Credentials
{
    const KEY_USERNAME = 'credentials.username';
    const KEY_PASSWORD = 'credentials.password';

    public function get(): CredentialsObject
    {
        /** @var Config $config */
        $config   = Robo::service('config');
        $username = $config->has(self::KEY_USERNAME)
            ? $config->get(self::KEY_USERNAME)
            : '<username>';
        $password = $config->has(self::KEY_PASSWORD)
            ? $config->get(self::KEY_PASSWORD)
            : '<password>';

        return new CredentialsObject($username, $password);
    }
}
