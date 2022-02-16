<?php


namespace Htk\Base\Library\Crypt\Key;

/**
 * AES秘钥类
 */
class AesKey implements ICryptKey
{
    /**
     * AES加解密KEY
     */
    public $passphrase;

    /**
     * 初始化向量
     */
    public $iv;

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        if (is_null($this->passphrase)) {
            throw new \Exception('AES algo require passphrase');
        }
        return $this->passphrase;
    }

    /**
     * 获取初始化向量
     * @return string
     * @author JohnsonKing
     */
    public function getIv(): string
    {
        if (!is_null($this->iv)) {
            $this->iv = substr($this->iv, 0, openssl_cipher_iv_length('AES-256-CBC'));
            return $this->iv;
        }
        $ivlen = openssl_cipher_iv_length('AES-256-CBC');
        $this->iv = openssl_random_pseudo_bytes($ivlen);
        return $this->iv;
    }
}