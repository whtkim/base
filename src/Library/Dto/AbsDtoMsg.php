<?php


namespace Htk\Base\Library\Dto;

use Htk\Base\Constant\Constant;

/**
 * Dto消息接口
 */
abstract class AbsDtoMsg
{
    /**
     * 获取指定语言版本的信息
     * @param string $key  msg对应的键名
     *
     * @return string|null
     * @author JohnsonKing
     */
    abstract public function trans(string $key);

    /**
     * 初始化操作
     * @param string $lang 指定语言
     *
     * @return static
     * @author JohnsonKing
     */
    public static function init(string $lang = Constant::LANG_DEFAULT)
    {
        $instance = new static();
        $instance->lang = $lang;
        return $instance;
    }
}