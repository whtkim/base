<?php


namespace Htk\Base\Library\Crypt\Algo;

use Htk\Base\Library\Crypt\Key\ICryptKey;

/**
 * 加解密算法接口
 */
interface ICryptAlgo
{
    /**
     * 对数据进行加密
     * @param string    $data     待加密数据
     * @param ICryptKey $cryptKey 秘钥实例
     * @param bool      $binary   返回二进制数据
     *
     * @return string|false
     * @author JohnsonKing
     */
    public function encrypt(string $data, ICryptKey $cryptKey, bool $binary = false);

    /**
     * 对密文进行解密
     * @param string    $data     待解密数据
     * @param ICryptKey $cryptKey 秘钥实例
     *
     * @return string|false
     * @author JohnsonKing
     */
    public function decrypt(string $data, ICryptKey $cryptKey);

    /**
     * 对数据进行签名
     * @param string    $data     数据
     * @param ICryptKey $cryptKey 签名实例
     *
     * @return string|false
     * @author JohnsonKing
     */
    public function sign(string $data, ICryptKey $cryptKey);

    /**
     * 验证签名合法性
     * @param string    $data      数据
     * @param string    $signature 签名
     * @param ICryptKey $cryptKey  秘钥实例
     *
     * @return int|false
     * @author JohnsonKing
     */
    public function verify(string $data, string $signature, ICryptKey $cryptKey);
}