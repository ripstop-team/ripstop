<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop;

use Robo\Robo;

trait NeedsCredentials
{

    protected function getCredentials(): Credentials
    {
        $credentials = Robo::service('credentials');

        return $credentials();
    }
}
