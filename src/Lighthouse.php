<?php

namespace phmLabs\LighthouseBridge;

use phmLabs\LighthouseBridge\Result\Result;

class Lighthouse
{
    const DEVICE_DESKTOP = 'desktop';
    const DEVICE_MOBILE = 'mobile';

    /**
     * @param $url
     * @param string $device
     * @return Result
     */
    public function process($url, $device = self::DEVICE_DESKTOP)
    {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(microtime());

        $execute = __DIR__ . '/../lighthouse/node_modules/.bin/lighthouse';

        $params = [
            '--output=html',
            '--output=json',
            '--output-path="' . $file . '"',
            '--save-assets',
            '--quiet',
            '--emulated-form-factor=' . $device,
            '--chrome-flags="--headless --no-sandbox --ignore-certificate-errors"',
            '--throttling-method=provided',
        ];

        $command = $execute . ' "' . $url . '" ' . implode(' ', $params) . ' 2>&1';

        exec($command, $output, $return_var);@

        $outputString = implode($output, "\n");

        if ($outputString != '' && strpos($output[0], 'UnhandledPromiseRejectionWarning') !== false) {
            $message = $output[0];
            throw new LighthouseException('Lighthouse exception: ' . $message);
        }

        $result = Result::fromFiles($file . '.report.json', $file . '.report.html');

        unlink($file . '.report.json');
        unlink($file . '.report.html');

        return $result;
    }
}
