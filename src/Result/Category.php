<?php

namespace phmLabs\LighthouseBridge\Result;

class Category
{
    const SCORE_UNDEFINED = 'undefined';

    private $categoryArray;

    public function __construct(array $categoryArray)
    {
        $this->categoryArray = $categoryArray;
    }

    public function getScore()
    {
        if ($this->categoryArray['score'] == 'null') {
            return self::SCORE_UNDEFINED;
        } else {
            return $this->categoryArray['score'] * 100;
        }
    }
}