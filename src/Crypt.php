<?php


namespace Htk\Base;

use Htk\Base\Library\Crypt\Algo\AesCrypt;
use Htk\Base\Library\Crypt\Algo\RsaCrypt;
use Htk\Base\Library\Crypt\Key\AesKey;
use Htk\Base\Library\Crypt\Key\RsaKey;
use Htk\Base\Library\Crypt\Key\ICryptKey;

/**
 * 加解密、签名入口类
 * @method string|false encrypt(string $data, ICryptKey $cryptKey, bool $binary = false) 数据加密
 * @method string|false decrypt(string $data, ICryptKey $cryptKey) 数据解密
 * @method string|false sign(string $data, ICryptKey $cryptKey) 数据签名
 * @method int|false verify(string $data, string $signature, ICryptKey $cryptKey) 验证签名合法性
 */
class Crypt
{
    public function __call($name, $arguments)
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof ICryptKey) {
                $cryptKeyClass = get_class($argument);
                switch ($cryptKeyClass) {
                    // AES算法
                    case AesKey::class:
                        return make(AesCrypt::class)->$name(...$arguments);
                    // RSA算法
                    case RsaKey::class:
                        return make(RsaCrypt::class)->$name(...$arguments);
                    // 默认
                    default:
                        throw new \Exception('No Support: ' . $cryptKeyClass);
                }
            }
        }
    }
}