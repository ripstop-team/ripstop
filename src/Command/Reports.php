<?php

namespace Ripstop\Command;

use Ripstop\ScanCollection;
use Robo\Robo;
use Robo\Tasks;

class Reports extends Tasks
{
    public function reportsSend(
        string $recipient,
        int $appId,
        int $scanId = null,
        $opts = [
            'subject|s' => 'Security vulnerability detected in your package {{ application_slug }}',
            'sender'    => 'noreply@example.com',
        ]
    ) {
        if ($scanId === null) {
            $scanId = $this->getLatestScanId($appId);
        }

        $filename = tempnam(sys_get_temp_dir(), 'ripstop') . '.pdf';

        $success = Robo::service('reports')($appId, $scanId, $filename);

        if ($success === false) {
            $this->yell("Couldn't fetch PDF report for Scan ID {$scanId} of Application ID {$appId}!", 40, 'red');

            return;
        }

        $data    = Robo::service('app_data')($appId, $scanId);
        $data    = ['application_slug' => 'test_plugin', 'application_version' => '1.0'];
        $success = Robo::service('emailer')($opts['subject'], $recipient, $opts['sender'], $filename, $data);

        unlink($filename);

        if ($success === false) {
            $this->yell("Couldn't send PDF report for Scan ID {$scanId} of Application ID {$appId}!", 40, 'red');

            return;
        }

        $this->say('PDF successfully sent.');
    }

    public function reportsPdf(int $appId, int $scanId = null, $opts = ['filename|f' => null])
    {
        if ($scanId === null) {
            $scanId = $this->getLatestScanId($appId);
        }

        if ($opts['filename'] === null) {
            $opts['filename'] = tempnam(sys_get_temp_dir(), 'ripstop') . '.pdf';
        }

        $success = Robo::service('reports')($appId, $scanId, $opts['filename']);
        if ($success === false) {
            $this->yell("Couldn't fetch PDF report for Scan ID {$scanId} of Application ID {$appId}!", 40, 'red');

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
