<?php


namespace Htk\Base\Library;

use Htk\Base\Exception\ClassNotFoundException;

/**
 * 类继承和接口实现检查
 */
class ClassCheck
{
    /**
     * 检查类是否继承与某类
     * @param string $className   当前类
     * @param string $parentClass 父类名称
     *
     * @return bool
     * @throws ClassNotFoundException
     * @author JohnsonKing
     */
    public static function isExtend(string $className, string $parentClass): bool
    {
        // 检查类是否存在
        if (!class_exists($className)) {
            throw new ClassNotFoundException($className);
        }

        // 检查类接口继承情况
        $parents = array_values(class_parents($className));
        if (!in_array($parentClass, $parents)) {
            return false;
        }

        return true;
    }

    /**
     * 检查类是否实现某接口
     * @param string $className 当前类
     * @param string $interface 接口名称
     *
     * @return bool
     * @throws ClassNotFoundException
     * @author JohnsonKing
     */
    public static function isImplement(string $className, string $interface): bool
    {
        // 检查类是否存在
        if (!class_exists($className)) {
            throw new ClassNotFoundException($className);
        }

        // 检查类接口继承情况
        $implements = array_values(class_implements($className));
        if (!in_array($interface, $implements)) {
            return false;
        }

        return true;
    }
}