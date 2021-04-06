<?php

namespace grigor\library\helpers;

use RuntimeException;
use Yii;

class DefinitionHelper
{
    public static function getDefinition(string $id): string
    {
        $definitions = Yii::$container->getDefinitions();

        if (!Yii::$container->has($id)) {
            throw new RuntimeException('Class ' . $id . ' is not registered correctly.');
        }

        return $definitions[$id]['class'];
    }
}