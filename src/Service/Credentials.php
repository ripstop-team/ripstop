<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Service;

use Robo\Config\Config;
use Robo\Robo;
use Ripstop\Credentials as CredentialsObject;

class Credentials
{
    const KEY_USERNAME = 'credentials.username';
    const KEY_PASSWORD = 'credentials.password';

    public function __invoke(): CredentialsObject
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
