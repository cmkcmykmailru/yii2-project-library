<?php

namespace grigor\library\contexts;

use RuntimeException;

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
            $this->container = new Container();
            $this->config->buildConfigsOfContext($this, $etcPaths);
            $diContext = $this->config->getDependenciesOfContext($this, $etcPaths);
            $this->container->setDefinitions($diContext['definition']);
            $this->container->setSingletons($diContext['singleton']);
        }
        return $this->container;
    }

    protected function config(array $etcPaths = []): Config
    {
        if ($this->config->isEmpty() || !empty($etcPaths)) {
            $this->config->buildConfigsOfContext($this, $etcPaths);
        }
        return $this->config;
    }

    public function getDefinitionOf(string $className): string
    {
        $container = $this->container();
        $definitions = $container->getDefinitions();

        if (!$container->has($className)) {
            throw new RuntimeException('Class ' . $className . ' is not registered correctly.');
        }

        return $definitions[$className]['class'];
    }

}