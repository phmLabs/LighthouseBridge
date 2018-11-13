<?php

namespace phmLabs\LighthouseBridge;

class Lighthouse
{
    public function check($url)
    {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(microtime()) . '-lighthouse-report.json';

        $execute = 'lighthouse';

        $params = [
            '--output=json',
            '--output-path=' . $file,
            '--save-assets',
            '--quite',
            '--chrome-flags="--headless"',
        ];

        $command = $execute . ' ' . $url . ' ' . implode(' ', $params);

        exec($command, $plainOutput, $return);

        $json = file_get_contents($file);
        $report = json_decode($json, true);
        unlink($file);

        return $report;

    }
}
