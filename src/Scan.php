<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop;

use DateTimeImmutable;

class Scan
{
    private $data;

    public static function fromAPIResponse($response): Scan
    {
        $scan     = new self($response);
        $scan->id = $response->id;

        return $scan;
    }

    private function __construct($data)
    {
        $this->data = [
            'id'             => $data->id,
            'version'        => $data->version,
            'start'          => new DateTimeImmutable($data->start),
            'finish'         => new DateTimeImmutable('@0'),
            'phase'          => $data->phase,
            'percent'        => $data->percent,
            'loc'            => $data->loc,
            'code_stored'    => $data->code_stored,
            'upload_removed' => $data->upload_removed,
            'source_type'    => $data->source_type,
        ];

        if (isset($data->finish)) {
            $this->data['finish'] = new DateTimeImmutable($data->finish);

        }
    }

    public function getId(): int
    {
        return $this->data['id'];
    }

    public function getVersion(): string
    {
        return $this->data['version'];
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->data['start'];
    }

    public function getFinish(): DateTimeImmutable
    {
        return $this->data['finish'];
    }

    public function getPhase(): int
    {
        return $this->data['phase'];
    }

    public function getPercent(): int
    {
        return $this->data['percent'];
    }

    public function getloc(): int
    {
        return $this->data['loc'];
    }

    public function isCodeStored(): bool
    {
        return $this->data['code_stored'];
    }

    public function isUploadRemoved(): bool
    {
        return $this->data['upload_removed'];
    }

    public function getSourceType(): int
    {
        return $this->data['source_type'];
    }
}
