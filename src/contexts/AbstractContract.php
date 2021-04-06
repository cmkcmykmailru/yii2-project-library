<?php

namespace grigor\library\contexts;

use Yii;
use yii\di\Container;

abstract class AbstractContract implements ContractInterface
{
    /** @var $container null|Container */
    private ?Container $container = null;
    private Config $config;

    /**
     * AbstractContext constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function container(array $etcPaths = []): Container
    {
        if ($this->container === null) {
            $this->container = Yii::$container;
            $this->config->buildConfigsOfContext($this, $etcPaths);
            $diContext = $this->config->getDependenciesOfContext($this, $etcPaths);
            $this->container->setDefinitions($diContext['definition']);
            $this->container->setSingletons($diContext['singleton']);
        }
        return $this->container;
    }

    protected function config(array $etcPaths = []): Config
    {
        $this->config->buildConfigsOfContext($this, $etcPaths);
        return $this->config;
    }
}