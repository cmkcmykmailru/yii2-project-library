<?php

namespace grigor\library\contexts;

use yii\base\InvalidConfigException;

class Config
{
    protected string $overridesDir;
    protected $config;

    /**
     * AbstractConfig constructor.
     * @param string $overridesDir
     */
    public function __construct(string $overridesDir)
    {
        $this->overridesDir = $overridesDir;
    }

    public function isEmpty(): bool
    {
        return empty($this->config);
    }

    public function getContextDirectory(AbstractContract $contract): string
    {
        return \dirname((new \ReflectionClass($contract))->getFileName());
    }

    public function buildConfigsOfContext(AbstractContract $contract, array $paths = []): void
    {
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

        $this->config = $config;
    }

    public function getDependenciesOfContext(AbstractContract $contract, array $paths = []): array
    {
        $dir = $this->getContextDirectory($contract);

        $etcPaths = empty($paths) ? include $dir . '/../etc/config.php' : $paths;
        $overridesPath = $this->overridesDir . '/overrides_di.php';
        $di = $this->getDependencies($etcPaths);

        if (file_exists($overridesPath)) {
            $overrides = include $overridesPath;
            $di['singleton'] = array_merge($di['singleton'], $overrides['singleton']);
            $di['definition'] = array_merge($di['definition'], $overrides['definition']);
        }

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

    public function contains(string $key): bool
    {
        if (empty($this->config)) {
            return false;
        }
        return array_key_exists($key, $this->config);
    }

    public function get(string $key)
    {
        if (!$this->contains($key)) {
            throw new InvalidConfigException('Check configuration.');
        }
        return $this->config[$key];
    }

    public function getKeys(): array
    {
        if ($this->isEmpty()) {
            return [];
        }
        return array_keys($this->config);
    }

    public function clear(): void
    {
        $this->config = [];
    }
}