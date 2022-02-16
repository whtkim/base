<?php


namespace Htk\Base\Library\Crypt\Algo;

use Htk\Base\Constant\CryptConst;
use Htk\Base\Base64;
use Htk\Base\Library\Crypt\Key\RsaKey;

/**
 * RSA加解密算法
 */
class RsaCrypt
{
    /**
     * @inheritDoc
     * @param RsaKey $cryptKey RSA秘钥实例
     */
    public function sign(string $data, $cryptKey): string
    {
        $priKey = $cryptKey->getKey();
        $algorithm = $cryptKey->algo === CryptConst::OPENSSL_RSA2 ? OPENSSL_ALGO_SHA256 : OPENSSL_ALGO_SHA1;
        $result = openssl_sign($data, $signature, $priKey, $algorithm);
        openssl_free_key($priKey);
        unset($priKey);
        return $result ? Base64::encode($signature) : false;
    }

    /**
     * @inheritDoc
     * @param RsaKey $cryptKey RSA秘钥实例
     */
    public function verify(string $data, string $signature, $cryptKey)
    {
        $pubKey = $cryptKey->getPublicKey(CryptConst::RSA_PUBKEY_RESOURCE);
        $signature = Base64::decode($signature);
        $algorithm = $cryptKey->algo === CryptConst::OPENSSL_RSA2 ? OPENSSL_ALGO_SHA256 : OPENSSL_ALGO_SHA1;
        $result = openssl_verify($data, $signature, $pubKey, $algorithm);
        openssl_free_key($pubKey);
        return $result;
    }

    /**
     * @inheritDoc
     * @param RsaKey $cryptKey RSA秘钥实例
     */
    public function encrypt(string $data, $cryptKey, bool $binary = false)
    {
        $keySource = $cryptKey->isPubKey ? $cryptKey->getKey() : $cryptKey->getPublicKey(CryptConst::RSA_PUBKEY_RESOURCE);
        $result = openssl_public_encrypt($data, $encryptData, $keySource);
        unset($data, $keySource);
        return $result ? ($binary ? $encryptData : Base64::encode($encryptData)) : false;
    }

    /**
     * @inheritDoc
     * @param RsaKey $cryptKey RSA秘钥实例
     */
    public function decrypt(string $data, $cryptKey): string
    {
        // 检查秘钥类型是否为公钥
        if (!$cryptKey instanceof RsaKey || $cryptKey->isPubKey) {
            throw new \Exception('RSA decrypt require private key');
        }

        // 解密操作
        $data = Base64::decode($data) ?: $data;
        $priKey = $cryptKey->getKey();
        $result = openssl_private_decrypt($data, $decryptData, $priKey);
        unset($data, $priKey);
        return $result ? $decryptData : false;
    }
}