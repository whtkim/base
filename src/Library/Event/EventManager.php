<?php


namespace Htk\Base\Library\Event;

use Htk\Base\Dto;
use Htk\Base\HtkUtils;
use Htk\Base\Library\Container;
use Htk\Base\Traits\__GetTrait;

/**
 * 事件管理器
 * @property string $eventName 事件名称
 */
abstract class EventManager implements IEvent
{
    use __GetTrait;

    // 事件名称
    private $eventName;

    // 订阅者列表
    private $subscribers = [];

    /**
     * EventManager constructor
     */
    public function __construct(string $eventName)
    {
        $class = HtkUtils::uncamelize(get_class($this));

        $this->eventName = $eventName ?: preg_replace('/_event$/', '', $class, 1);
    }

    /**
     * @inheritDoc
     */
    public static function create(string $eventName = '', bool $isGlobal = false)
    {
        return $isGlobal ? Container::make(static::class, [$eventName]) : (new static($eventName));
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(Subscriber $subscriber)
    {
        $this->subscribers[$subscriber->tagName] = $subscriber;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeSubscriber(string $subscriberName): bool
    {
        if (isset($this->subscribers[$subscriberName])) {
            unset($this->subscribers[$subscriberName]);
        }
        return true;
    }

    /**
     * 事件触发通知
     * @return Dto
     */
    protected function notify(): Dto
    {
        // 通知订阅者
        $errors = [];
        /** @var Subscriber $subscriber */
        foreach ($this->subscribers as $subscriber) {
            $result = $subscriber->handle($this);
            if (!$result->isSuccess()) {
                $errors[] = [
                    self::NOTIFY_SUBSCRIBER_NAME => $subscriber->tagName,
                    self::NOTIFY_ERROR_MESSAGE   => $result->getMessage()
                ];
            }
        }
        // 返回结果
        return empty($errors) ? Dto::success() : Dto::fail('event notify handle failed')->setData($errors);
    }
}