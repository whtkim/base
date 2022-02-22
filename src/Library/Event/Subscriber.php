<?php


namespace Htk\Base\Library\Event;

use Htk\Base\Library\Container;
use Htk\Base\Traits\__GetTrait;

/**
 * 事件订阅抽象类
 * @property string $tagName 订阅者标签名
 */
abstract class Subscriber implements ISubscriber
{
    use __GetTrait;

    /**
     * 订阅者标签名
     * @public
     */
    private $tagName;

    /**
     * Subscriber constructor.
     * @param string|null $tagName 标签名
     */
    public function __construct(?string $tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * 创建订阅者实例对象
     * @param string|null $tagName 标签名
     *
     * @return $this
     * @author JohnsonKing
     */
    public static function create(?string $tagName = null)
    {
        return Container::make(static::class, [$tagName]);
    }
}