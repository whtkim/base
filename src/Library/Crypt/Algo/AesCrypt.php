<?php


namespace Htk\Base\Library\Crypt\Algo;

use Htk\Base\Base64;
use Htk\Base\Library\Crypt\Key\AesKey;
use Htk\Base\Library\Crypt\Key\ICryptKey;

/**
 * AES加解密算法
 */
class AesCrypt implements ICryptAlgo
{
    /**
     * @inheritDoc
     * @param AesKey $cryptKey AES秘钥实例
     */
    public function encrypt(string $data, $cryptKey, bool $binary = false)
    {
        $data = openssl_encrypt($data, 'AES-256-CBC', $cryptKey->getKey(), OPENSSL_RAW_DATA, $cryptKey->getIv());
        return $binary ? $data : Base64::encode($data);
    }

    /**
     * @inheritDoc
     * @param AesKey $cryptKey AES秘钥实例
     */
    public function decrypt(string $data, $cryptKey)
    {
        $data = Base64::decode($data) ?: $data;
        return openssl_decrypt($data, 'AES-256-CBC', $cryptKey->getKey(),OPENSSL_RAW_DATA, $cryptKey->getIv());
    }

    /**
     * @inheritDoc
     * @param AesKey $cryptKey AES秘钥实例
     */
    public function sign(string $data, $cryptKey)
    {
        $encryptData = $this->encrypt($data, $cryptKey);
        if ($encryptData === false) return false;
        $hash = hash_hmac('sha256', $encryptData, $cryptKey->getIv(), true);
        return $hash === false ? false : Base64::hash($hash);
    }

    /**
     * @inheritDoc
     * @param AesKey $cryptKey AES秘钥实例
     */
    public function verify(string $data, string $signature, ICryptKey $cryptKey)
    {
        $calcSign = $this->sign($data, $cryptKey);
        if ($calcSign === false) return false;
        return $calcSign === $signature ? 1 : false;
    }
}