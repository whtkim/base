<?php


namespace Htk\Base\Constant;


class Constant
{
    #---------- 语言包 ----------#
    /**
     * 英文语言包
     */
    const LANG_EN = 'en';

    /**
     * 中文语言包
     */
    const LANG_CN = 'zh_CN';

    /**
     * 默认语言包
     */
    const LANG_DEFAULT = self::LANG_CN;
    

    #---------- 服务健康状态 ----------#
    /**
     * 绿色（正常）
     */
    const HEALTH_LEVEL_GREEN = 4;

    /**
     * 黄色（基本正常）
     */
    const HEALTH_LEVEL_YELLOW = 3;

    /**
     * 橙色（警告）
     */
    const HEALTH_LEVEL_ORANGE = 2;

    /**
     * 红色（超负荷）
     */
    const HEALTH_LEVEL_RED = 1;
}