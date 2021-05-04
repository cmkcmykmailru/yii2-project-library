<?php

namespace grigor\library\dispatcher;

interface EventDispatcherInterface
{
    public function addEvent($event): void;

    public function addAllEvents(array $events): void;

    public function publish(): void;

    public function clear(): void;
}