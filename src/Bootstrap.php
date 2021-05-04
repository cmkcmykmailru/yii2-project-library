<?php
namespace grigor\library;

use grigor\library\contexts\ContractWrapper;
use grigor\library\contexts\InflateSwitcher;
use grigor\library\factories\LatSlugFactory;
use grigor\library\factories\SlugFactoryInterface;
use grigor\library\repositories\strategies\BaseDeleteStrategy;
use grigor\library\repositories\strategies\BaseSaveStrategy;
use grigor\library\repositories\strategies\DeleteStrategyInterface;
use grigor\library\repositories\strategies\SaveStrategyInterface;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $container = \Yii::$container;
    }
}