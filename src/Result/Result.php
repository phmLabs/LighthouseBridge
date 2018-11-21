<?php

namespace phmLabs\LighthouseBridge\Result;

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
        $json = file_get_contents($jsonFile);
        $reportArray = json_decode($json, true);

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
