<?php


namespace Htk\Base\Library\Event;

use Htk\Base\Dto;

/**
 * 事件订阅接口
 * @author JohnsonKing
 */
interface ISubscriber
{
    public function handle(EventManager $event): Dto;
}