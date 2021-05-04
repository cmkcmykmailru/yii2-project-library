<?php

namespace grigor\library\contexts;

use yii\base\InvalidConfigException;
use yii\di\Container as BaseContainer;

class Container extends BaseContainer
{
    public function getDefinitionOf(string $className): string
    {
        $definitions = $this->getDefinitions();

        if (!$this->has($className)) {
            throw new InvalidConfigException('Class ' . $className . ' is not registered correctly.');
        }

        return $definitions[$className]['class'];
    }
}