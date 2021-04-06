<?php

namespace grigor\library\dispatcher;

use yii\queue\JobInterface;

class Job implements JobInterface
{
    /**
     * @var mixed
     */
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function execute($queue): void
    {
        $executor = $this->buildExecutor();
        $executor->handle($this, $queue);
    }

    private function buildExecutor(): JobExecutor
    {
        return \Yii::createObject(JobExecutor::class);
    }
}