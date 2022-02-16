<?php


namespace Htk\Base\Library\Crypt;

use Htk\Base\Constant\CryptConst;
use Htk\Base\Library\Crypt\Key\AesKey;
use Htk\Base\Library\Crypt\Key\ICryptKey;
use Htk\Base\Library\Crypt\Key\RsaKey;
use Htk\Base\Library\Singleton;

/**
 * 秘钥仓库(快速获取秘钥)
 */
class Keystore extends Singleton
{
    // 算法映射列表
    private static $algoMaps = [
        CryptConst::OPENSSL_RSA        => RsaKey::class,
        CryptConst::OPENSSL_RSA2       => RsaKey::class,
        CryptConst::OPENSSL_AES256_CBC => AesKey::class,
    ];

    /**
     * 初始化操作
     * @param int         $algo     算法类
     * @param string|null $key      秘钥或其文件路径, 为空则取默认文件
     * @param bool        $isPubKey 是否公钥
     *
     * @return ICryptKey
     * @author JohnsonKing
     */
    public static function init(int $algo, ?string $key = null, bool $isPubKey = false)
    {
        // AES算法
        $cryptKeyClass  = self::$algoMaps[$algo];
        $cryptKeyObject = new $cryptKeyClass();
        if ($algo === CryptConst::OPENSSL_AES256_CBC) {
            $cryptKeyObject->passphrase = $key;
            return $cryptKeyObject;
        }

        // RSA算法
        $cryptKeyObject->algo = $algo;
        $cryptKeyObject->isPubKey = $isPubKey;
        if (is_string($key) && !file_exists($key)) {
            [$cryptKeyObject->publicKey, $cryptKeyObject->privateKey] = $isPubKey ? [$key, null] : [null, $key];
        } else {
            $keyFile = $isPubKey ? 'public.pem' : 'private.pem';
            $keyPath = is_null($key) ? config('crypt_keystore_path') . "/{$keyFile}" : $key;
            [$cryptKeyObject->publicKeyPath, $cryptKeyObject->privateKeyPath] = $isPubKey ? [$keyPath, null] : [null, $keyPath];
        }
        return $cryptKeyObject;
    }
}