<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop;

use DateTimeImmutable;

class Application
{
    private $data;

    public static function fromAPIResponse($response): self
    {
        $application     = new self($response);
        $application->id = $response->id;

        return $application;
    }

    private function __construct($data)
    {
        $this->data['id']           = $data->id;
        $this->data['name']         = $data->name;
        $this->data['creationDate'] = new DateTimeImmutable($data->creation);
    }

    public function getId(): int
    {
        return $this->data['id'];
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->data['creationDate'];
    }
}
