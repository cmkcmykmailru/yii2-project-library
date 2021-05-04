<?php

namespace grigor\library\dispatcher;

use yii\queue\Queue;

class EventDispatcher implements EventDispatcherInterface
{
    private array $events;
    private Queue $queue;

    /**
     * EventDispatcher constructor.
     */
    public function __construct(Queue $queue)
    {
        $this->events = [];
        $this->queue = $queue;
    }

    public function addEvent($event): void
    {
        $this->events[] = $event;
    }

    public function addAllEvents(array $events): void
    {
        $this->events = $this->events + $events;
    }

    public function publish(): void
    {
        foreach ($this->events as $event) {
            $this->queue->push($event);
        }
        $this->clear();
    }

    public function clear(): void
    {
        $this->events = [];
    }
}