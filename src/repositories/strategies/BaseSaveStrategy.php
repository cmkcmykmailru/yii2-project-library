<?php

namespace grigor\library\repositories\strategies;

class BaseSaveStrategy implements SaveStrategyInterface
{
    public function save($object)
    {
        if (!$object->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}