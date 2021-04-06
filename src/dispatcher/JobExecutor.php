<?php

namespace grigor\library\dispatcher;

use yii\di\Container;

class JobExecutor
{
    private Container $container;
    private array $listeners;

    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    public function handle(Job $job, $queue): void
    {
        $event = $job->event;
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $this->fire($listenerClass, $event, $queue);
            }
        }
    }

    private function fire($listenerClass, $event, $queue): void
    {
        $listener = $this->container->get($listenerClass);
        $listener->handle($event, $queue);
    }
}