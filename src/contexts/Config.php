<?php

namespace grigor\library\contexts;

use yii\caching\Cache;
use yii\caching\TagDependency;

class Config
{
    protected Registry $registry;
    protected string $overridesDir;

    /**
     * AbstractConfig constructor.
     * @param $config
     */
    public function __construct(string $overridesDir)
    {
        $this->registry = new Registry();
        $this->overridesDir = $overridesDir;
    }

    public function getContextDirectory(AbstractContract $contract): string
    {
        $key = get_class($contract) . 'Directory';
        if ($this->registry->has($key)) {
            return $this->registry->get($key);
        }
        $dir = \dirname((new \ReflectionClass($contract))->getFileName());
        $this->registry->register($key, $dir);
        return $dir;
    }

    public function buildConfigsOfContext(AbstractContract $contract, array $paths = []): void
    {
        $key = get_class($contract) . 'Configs';
        if ($this->registry->has($key)) {
            return;
        }

        $dir = $this->getContextDirectory($contract);
        $etcPaths = empty($paths) ? include $dir . '/../etc/config.php' : $paths;
        $overridesPath = $this->overridesDir . '/overrides_config.php';

        $config = [];

        foreach ($etcPaths as $path) {
            $alias = \Yii::getAlias($path);
            $configPath = $alias . '/config.php';
            $configs = include $configPath;
            $config = array_merge($config, $configs);
        }

        if (file_exists($overridesPath)) {
            $overrides = include $overridesPath;
            $config = array_merge($config, $overrides);
        }


        $this->registry->register($key, $config);
        return;
    }

    public function getDependenciesOfContext(AbstractContract $contract, array $paths = []): array
    {
        $key = get_class($contract) . 'Dependencies';
        if ($this->registry->has($key)) {
            return $this->registry->get($key);
        }
        $dir = $this->getContextDirectory($contract);


        $etcPaths = empty($paths) ? include $dir . '/../etc/config.php' : $paths;
        $overridesPath = $this->overridesDir . '/overrides_di.php';
        $di = $this->getDependencies($etcPaths);

        if (file_exists($overridesPath)) {
            $overrides = include $overridesPath;
            $di['singleton'] = array_merge($di['singleton'], $overrides['singleton']);
            $di['definition'] = array_merge($di['definition'], $overrides['definition']);
        }

        $this->registry->register($key, $di);
        return $di;
    }

    protected function getDependencies(array $paths): array
    {
        $singleton = [];
        $definition = [];
        foreach ($paths as $path) {
            $alias = \Yii::getAlias($path);
            $singletonPath = $alias . '/singleton.php';
            $definitionPath = $alias . '/definition.php';

            $singletons = include $singletonPath;
            $definitions = include $definitionPath;

            $singleton = array_merge($singleton, $singletons);
            $definition = array_merge($definition, $definitions);
        }

        return [
            'singleton' => $singleton,
            'definition' => $definition
        ];
    }

}