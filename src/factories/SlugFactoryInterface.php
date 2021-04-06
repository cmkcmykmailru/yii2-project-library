<?php

namespace grigor\library\factories;

interface SlugFactoryInterface
{
    public function toSlug(string $string): string;
}