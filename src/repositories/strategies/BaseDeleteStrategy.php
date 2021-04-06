<?php

namespace grigor\library\repositories\strategies;

class BaseDeleteStrategy implements DeleteStrategyInterface
{

    public function delete($object)
    {
        if (!$object->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

}