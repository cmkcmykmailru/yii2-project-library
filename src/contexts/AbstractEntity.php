<?php

namespace grigor\library\contexts;

use yii\db\ActiveRecord;

abstract class AbstractEntity extends ActiveRecord
{

    public static function instance($refresh = false): self
    {
        static $instance;
        return $refresh || !$instance ? $instance = self::instantiate([]) : $instance;
    }

    public static function instantiate($row): self
    {
        static $prototype;
        if ($prototype === null) {
            $calledClass = get_called_class();
            $prototype = unserialize(sprintf('O:%d:"%s":0:{}', \strlen($calledClass), $calledClass));
        }
        $object = clone $prototype;
        $object->init();
        return $object;
    }

}