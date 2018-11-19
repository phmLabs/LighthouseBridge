<?php

namespace phmLabs\LighthouseBridge\Result;

class Category
{
    private $categoryArray;

    public function __construct(array $categoryArray)
    {
        $this->categoryArray = $categoryArray;
    }

    public function getScore()
    {
        return $this->categoryArray['score'] * 100;
    }
}