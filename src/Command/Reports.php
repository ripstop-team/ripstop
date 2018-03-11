<?php

/*
 * Copyright (c) Ripstop Contributors. All rights reserved.
 * Licensed under the MIT License. See LICENSE.md file in the
 * project root for full license information.
 */

namespace Ripstop\Command;

use Ripstop\ScanCollection;
use Robo\Robo;
use Robo\Tasks;

class Reports extends Tasks
{
    public function reportsSend(
        string $recipient,
        $application,
        int $scanId = null,
        $opts = [
            'subject|s' => 'Security vulnerability detected in your package {{ application_slug }}',
            'sender'    => 'noreply@example.com',
        ]
    ) {
        if ( ! is_numeric($application)) {
            /** @var \Ripstop\Application $application */
            $application = Robo::service('applicationForName')($application);
        }

        if ($scanId === null) {
            $scanId = $this->getLatestScanId($application->getId());
        }

        $data = Robo::service('app_data')($application->getId(), $scanId);

        $prefix   = "sec_report_{$data['application_slug']}_{$data['application_version']}_";
        $filename = tempnam(sys_get_temp_dir(), $prefix) . '.pdf';

        $success = Robo::service('reports')($application->getId(), $scanId, $filename);

        if ($success === false) {
            $this->yell("Couldn't fetch PDF report for Scan ID {$scanId} of Application ID {$application->getId()}!", 40, 'red');

            return;
        }

        $success = Robo::service('emailer')($opts['subject'], $recipient, $opts['sender'], $filename, $data);

        unlink($filename);

        if ($success === false) {
            $this->yell("Couldn't send PDF report for Scan ID {$scanId} of Application ID {$application->getId()}!", 40, 'red');

            return;
        }

        $this->say('PDF successfully sent.');
    }

    public function reportsPdf($application, int $scanId = null, $opts = ['filename|f' => null])
    {
        if ( ! is_numeric($application)) {
            /** @var \Ripstop\Application $application */
            $application = Robo::service('applicationForName')($application);
        }

        if ($scanId === null) {
            $scanId = $this->getLatestScanId($application->getId());
        }

        if ($opts['filename'] === null) {
            $opts['filename'] = tempnam(sys_get_temp_dir(), 'ripstop') . '.pdf';
        }

        $success = Robo::service('reports')($application->getId(), $scanId, $opts['filename']);
        if ($success === false) {
            $this->yell("Couldn't fetch PDF report for Scan ID {$scanId} of Application ID {$application->getId()}!", 40, 'red');

            return;
        }

        $this->say("PDF generated successfully: {$opts['filename']}");
    }

    private function getLatestScanId(int $appId): int
    {
        $scanService = Robo::service('scans');

        /** @var ScanCollection $scans */
        $scans = $scanService->latest($appId, 1);
        if ($scans->count() < 1) {
            $this->yell("No scans found for Application ID {$appId}!", 40, 'red');
            exit();
        }

        return $scans[0]->id;
    }
}
