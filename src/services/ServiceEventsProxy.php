<?php

namespace grigor\library\services;

use yii\base\Component;
use yii\base\Event;

class ServiceEventsProxy extends Component implements Service
{
    public const EVENT_AFTER_METHOD_EXECUTE = 'afterMethodExecute';
    public const EVENT_BEFORE_METHOD_EXECUTE = 'beforeMethodExecute';
    public const EVENT_ERROR_METHOD_EXECUTE = 'errorMethodExecute';
    protected $realService;

    public function __construct(
        Service $realService,
        $config = []
    )
    {
        $this->realService = $realService;
        parent::__construct($config);
    }

    protected function wrap(callable $callback, array $arguments, array $eventNames)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            Event::trigger(ServiceEventsProxy::class, self::EVENT_BEFORE_METHOD_EXECUTE, new Event([
                'name' => $eventNames[self::EVENT_BEFORE_METHOD_EXECUTE],
                'sender' => $this->realService,
                'data' => $arguments
            ]));

            $args = array_values($arguments);
            $result = call_user_func_array($callback, $args);
            $transaction->commit();

            Event::trigger(ServiceEventsProxy::class, self::EVENT_AFTER_METHOD_EXECUTE, new Event([
                'name' => $eventNames[self::EVENT_AFTER_METHOD_EXECUTE],
                'sender' => $this->realService,
                'data' => $arguments
            ]));
        } catch (\Exception $e) {
            $transaction->rollBack();

            Event::trigger(ServiceEventsProxy::class, self::EVENT_ERROR_METHOD_EXECUTE, new Event([
                'name' => $eventNames[self::EVENT_ERROR_METHOD_EXECUTE],
                'sender' => $this->realService,
                'data' => $arguments
            ]));

            throw $e;
        }
        return $result;
    }
}