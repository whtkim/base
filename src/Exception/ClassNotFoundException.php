<?php


namespace Htk\Base\Exception;

/**
 * 类不存在异常
 * @package Htk\Base\Exception
 */
class ClassNotFoundException extends \Exception
{
    /**
     * 发生异常的类名
     */
    private $className;

    /**
     * ClassNotFoundException constructor
     * @param string         $className 类名
     * @param int            $code      异常编码
     * @param Throwable|null $previous  上级异常
     */
    public function __construct(string $className, $code = 0, ?Throwable $previous = null)
    {
        $this->className = $className;
        $message = "class {$className} not found";
        parent::__construct($message, $code, $previous);
    }

    /**
     * 获取异常类
     * @return string
     * @author JohnsonKing
     */
    final public function getClass(): string
    {
        return $this->className;
    }
}