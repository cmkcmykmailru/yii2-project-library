<?php

namespace grigor\library\factories;

use yii\helpers\BaseInflector;

class LatSlugFactory implements SlugFactoryInterface
{

    public function toSlug(string $string): string
    {
        return BaseInflector::slug($string);
    }
}