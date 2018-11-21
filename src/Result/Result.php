<?php

namespace phmLabs\LighthouseBridge\Result;

use phmLabs\LighthouseBridge\LighthouseException;

class Result
{
    private $resultArray;
    private $htmlContent = '';

    const CATEGORY_PERFORMANCE = 'performance';
    const CATEGORY_PWA = 'pwa';
    const CATEGORY_SEO = 'seo';
    const CATEGORY_ACCESSIBILITY = 'accessibility';
    const CATEGORY_BEST_PRACTICES = 'best-practices';

    public function __construct($resultArray, $htmlContent)
    {
        $this->resultArray = $resultArray;
        $this->htmlContent = $htmlContent;
    }

    public function getCategory($categoryName)
    {
        if (!array_key_exists($categoryName, $this->resultArray['categories'])) {
            $categories = implode(', ', array_keys($this->resultArray['categories']));
            throw new \RuntimeException('Given category ("' . $categoryName . '") not found. Possible categories are ' . $categories . '.');
        }

        $categoryArray = $this->resultArray['categories'][$categoryName];
        return new Category($categoryArray);
    }

    /**
     * @param $jsonFile
     * @return Result
     */
    public static function fromFiles($jsonFile, $htmlFile)
    {
        if (file_exists($jsonFile)) {
            $json = file_get_contents($jsonFile);
        } else {
            throw new LighthouseException('Json file not found.');
        }

        if ($json == "") {
            throw new LighthouseException('The given json is empty.');
        }

        $reportArray = json_decode($json, true);

        if (count($reportArray) == 0) {
            throw new LighthouseException('The given json response is not valid json.');
        }

        $htmlContent = file_get_contents($htmlFile);

        return new self($reportArray, $htmlContent);
    }

    public function getHtmlReport()
    {
        return $this->htmlContent;
    }

    public function getJsonReport()
    {
        $jsonString = json_encode($this->resultArray, JSON_PRETTY_PRINT);;
        return $jsonString;
    }
}
