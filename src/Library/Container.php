<?php


namespace Htk\Base\Library;

use Htk\Base\Exception\ClassNotFoundException;


class Container extends Singleton
{
    // 实例列表
    private $instances = [];

    /**
     * 获取指定类的单例对象
     * @param string $class     类名(含命名空间)
     * @param array  $arguments 构造函数参数
     * @throws ClassNotFoundException
     */
    public static function make(string $class, array $arguments = [])
    {
        if (!class_exists($class)) {
            throw new ClassNotFoundException($class);
        }

        $classKey  = md5($class);
        $container = self::getSingleton();
        if (!isset($container->instances[$classKey])) {
            $container->instances[$classKey] = new $class(...$arguments);
        }

        return $container->instances[$classKey];
    }
}