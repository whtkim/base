<?php


namespace Htk\Base\Library\Dto;

use Htk\Base\Constant\Constant;
use Htk\Base\Library\ClassCheck;

/**
 * DTO基类
 */
class DtoBase
{
    /**
     * @var int 状态码
     */
    protected $code;

    /**
     * @var string 提示信息
     */
    protected $message;

    /**
     * @var mixed 数据
     */
    protected $data;

    /**
     * @var bool 是否成功
     */
    protected $isSucess;

    /**
     * @var string 语言包
     */
    protected $lang = Constant::LANG_DEFAULT;

    /**
     * 设置状态码
     * @param int       $code      状态码
     * @param bool|null $isSuccess 是否成功
     *
     * @return $this
     * @author JohnsonKing
     */
    public function setCode(int $code, ?bool $isSuccess = null): self
    {
        $this->code = $code;
        if (!is_null($isSuccess)) {
            $this->isSucess = $isSuccess;
        }
        return $this;
    }

    /**
     * 获取状态码
     * @return int
     * @author JohnsonKing
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 设置提示信息
     * @param string|null $message 提示信息
     * 
     * @return $this
     * @author JohnsonKing
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 获取提示信息
     * @return string
     * @author JohnsonKing
     */
    public function getMessage(): string
    {
        // 检查是否设置message
        if (!is_null($this->message)) {
            return $this->message;
        }
        // 获取指定语言版本的消息
        $key = "message.{$this->code}";
        return $this->lang instanceof AbsDtoMsg ? $this->lang->trans($key) : \trans($key, [], $this->lang);
    }

    /**
     * 设置数据
     * @param mixed $data 数据
     *
     * @return $this
     * @author JohnsonKing
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 获取数据
     * @return mixed
     * @author JohnsonKing
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置显示语言
     * @param string|AbsDtoMsg $lang 语言|继承AbsDtoMsg的实例
     * 
     * @return $this
     * @author JohnsonKing
     */
    public function setLang($lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * 将数据转为数组
     * @return array
     * @author JohnsonKing
     */
    public function toArray(): array
    {
        return [
            'code'    => $this->code,
            'message' => $this->getMessage(),
            'data'    => $this->data,
        ];
    }

    /**
     * 将数据转为JSON
     * @return string
     * @author JohnsonKing
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}