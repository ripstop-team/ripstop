<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop;

use DateTimeImmutable;

class Upload
{
    private $data;

    public static function fromAPIResponse($response): self
    {
        return new self($response);
    }

    private function __construct($response)
    {
        $this->data = [
            'id'         => $response->id,
            'submission' => new DateTimeImmutable($response->submission),
            'name'       => $response->name,
            'extension'  => $response->extension,
            'size'       => $response->size,
        ];
    }

    public function getId(): int
    {
        return $this->data['id'];
    }

    public function getSubmission(): DateTimeImmutable
    {
        return $this->data['submission'];
    }

    public function getName(): string
    {
        return $this->data['string'];
    }

    public function getFile(): string
    {
        return $this->data['file'];
    }

    public function getExtension(): string
    {
        return $this->data['extension'];
    }

    public function getSize(): int
    {
        return $this->data['size'];
    }
}
