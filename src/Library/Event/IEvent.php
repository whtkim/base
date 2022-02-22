<?php


namespace Htk\Base\Library\Event;

/**
 * 事件接口
 * @author JohnsonKing
 */
interface IEvent
{
    /**
     * 事件通知-订阅者名称
     */
    const NOTIFY_SUBSCRIBER_NAME = 'name';

    /**
     * 事件通知-错误信息
     */
    const NOTIFY_ERROR_MESSAGE = 'message';

    /**
     * 创建事件
     * @param string $eventName 事件名：空字符串则为类名去驼峰化
     * @param bool   $isGlobal  是否为全局事件
     */
    public static function create(string $eventName = '', bool $isGlobal = false);

    /**
     * 添加事件订阅者
     * @param Subscriber $subscriber 事件订阅者
     */
    public function addSubscriber(Subscriber $subscriber);

    /**
     * 删除事件订阅者
     * @param string $subscriberName 订阅者名称
     *
     * @return bool
     * @author JohnsonKing
     */
    public function removeSubscriber(string $subscriberName): bool;

    /**
     * 触发事件
     */
    public function trigger();
}