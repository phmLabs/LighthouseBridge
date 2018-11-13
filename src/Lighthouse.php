<?php

namespace phmLabs\LighthouseBridge;

class Lighthouse
{
    public function check($url)
    {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(microtime()) . '-lighthouse-report.json';
        file_put_contents($file, $htmlContent);

        $execute = 'lighthouse';

        $params = [
            '--output=json',
            '--output-path=' . $file,
            '--save-assets',
            '--quite',
            '--chrome-flags="--headless"',
        ];

        $command = $execute . ' ' . $url . implode(' ', $params);

        exec($command, $plainOutput, $return);

        $report = json_decode($file, true);
        unlink($file);

        return $report;

    }
}
