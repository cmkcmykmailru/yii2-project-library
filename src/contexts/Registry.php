<?php

namespace grigor\library\contexts;

class Registry
{
    private array $data = [];

    public function register(string $key, $data): void
    {
        $this->data[$key] = $data;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key)
    {
        if (!$this->has($key)) {
            throw new \RuntimeException('Key not found.');
        }
        return $this->data[$key];
    }

    public function clear(): void
    {
        $this->data = [];
    }
}