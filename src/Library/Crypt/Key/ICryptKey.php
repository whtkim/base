<?php


namespace Htk\Base\Library\Crypt\Key;

/**
 * 秘钥接口
 */
interface ICryptKey
{
    /**
     * 获取秘钥（资源）
     * @return resource|string|false
     * @author JohnsonKing
     */
    public function getKey();
}