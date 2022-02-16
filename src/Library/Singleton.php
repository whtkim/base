<?php


namespace Htk\Base\Library;

/**
 * 单例模式
 */
abstract class Singleton
{
    private static $instance;

    private function __clone() {}

    private function __construct() {}

    /**
     * 获取单例对象
     * @return static
     */
    public static function getSingleton()
    {
        if (!self::$instance instanceof static) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}