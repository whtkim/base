<?php


namespace Htk\Base;

use Htk\Base\Constant\RespConst;
use Htk\Base\Library\Dto\DtoBase;

/**
 * DTO快速操作类
 */
class Dto extends DtoBase
{
    /**
     * 空字符串
     */
    const EMPTY_STRING = '';

    /**
     * 执行成功
     * @param mixed $data 数据
     * @param int   $code 状态码
     *
     * @return $this
     * @author JohnsonKing
     */
    public static function success($data = null, int $code = RespConst::SUCCESS): self
    {
        return (new self())->setCode($code, true)->setData($data);
    }

    /**
     * 执行失败
     * @param string|null $message 提示信息
     * @param int         $code    状态码
     *
     * @return $this
     * @author JohnsonKing
     */
    public static function fail(?string $message = null, int $code = RespConst::FAILED): self
    {
        return (new self())->setCode($code, false)->setMessage($message);
    }

    /**
     * 检查执行是否成功
     * @return bool
     * @author JohnsonKing
     */
    public function isSuccess(): bool
    {
        return $this->isSucess || $this->code === RespConst::SUCCESS;
    }
}