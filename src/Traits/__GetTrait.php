<?php


namespace Htk\Base\Traits;

use Htk\Base\Library\Container;

/**
 * 自动暴露私有和保护属性
 *
 * @desc 1.在类注释上添加@property
 *       2.各个元素间通过单个空格隔开
 *       3.示例：@property string $name 姓名
 */
trait __GetTrait
{
    public function __get(string $name)
    {
        /** @var \ReflectionClass $reflection */
        $reflection = Container::make('\\ReflectionClass', [get_class()]);
        $documents = $reflection->getDocComment();
        $matchNum = preg_match_all('/@property ([a-z]+) \$([a-zA-Z0-9_]+) (.*)/', $documents, $matches);
        if ($matchNum > 0 && in_array($name, $matches[2])) {
            return $this->$name;
        }
    }
}