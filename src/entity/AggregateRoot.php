<?php

namespace grigor\library\entity;

interface AggregateRoot
{
    public function releaseEvents(): array;
}