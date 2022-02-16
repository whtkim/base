<?php


namespace Htk\Base;


class Base64
{
    /**
     * BASE64编码
     * @param string $data 待编码数据
     *
     * @return string
     * @author JohnsonKing
     */
    public static function encode(string $data): string
    {
        return str_replace('+', '-', base64_encode($data));
    }

    /**
     * BASE64解码
     * @param string $data 待解码数据
     *
     * @return false|string
     * @author JohnsonKing
     */
    public static function decode(string $data)
    {
        $data = str_replace('-', '+', $data);
        return base64_decode($data);
    }

    /**
     * BASE编码（不含特殊符号）
     * @param string $data 待编码数据
     *
     * @return string
     * @author JohnsonKing
     */
    public static function hash(string $data): string
    {
        return str_replace(['+', '-', '/', '='], '', base64_encode($data));
    }
}