<?php


namespace Htk\Base\Library\Crypt\Key;

use Htk\Base\Constant\CryptConst;

/**
 * RSA秘钥类
 */
class RsaKey implements ICryptKey
{
    /**
     * 加密算法
     */
    public $algo = CryptConst::OPENSSL_RSA2;

    /**
     * 秘钥类型
     */
    public $isPubKey = false;

    /**
     * 私钥密码
     */
    public $passphrase;

    /**
     * 公钥
     */
    public $publicKey;

    /**
     * 私钥
     */
    public $privateKey;

    /**
     * 公钥路径
     */
    public $publicKeyPath;

    /**
     * 私钥路径
     */
    public $privateKeyPath;

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        // 从文件中提取秘钥
        $keyType = $this->isPubKey ? 'public' : 'private';
        $keyProp = "{$keyType}Key";
        if (is_null($this->$keyProp)) {
            $fetchKeyFunc = "openssl_get_{$keyType}key";
            $keyPathProp = "{$keyProp}Path";
            return $fetchKeyFunc(file_get_contents($this->$keyPathProp), $this->passphrase);
        }

        // 字符串中提取秘钥
        return $this->parseKey($this->$keyProp, $keyType);
    }

    /**
     * 获取公钥
     * @param int $retKeyType 返回公钥形式
     *
     * @return string|resource|false
     * @author JohnsonKing
     */
    public function getPublicKey(int $retKeyType = CryptConst::RSA_PUBKEY_ONE_ROW)
    {
        // 秘钥本身为公钥，返回资源型公钥
        $keyResource = $this->getKey();
        if ($this->isPubKey && $retKeyType === CryptConst::RSA_PUBKEY_RESOURCE) {
            return $keyResource;
        }
        // 秘钥本身为私钥或返回文本型公钥
        $keyDetail = openssl_pkey_get_details($keyResource);
        unset($keyResource);
        if ($retKeyType === CryptConst::RSA_PUBKEY_RESOURCE) return openssl_get_publickey($keyDetail['key']);
        return $retKeyType === CryptConst::RSA_PUBKEY_MULTI_ROWS ? $keyDetail['key'] : $this->parseKey($keyDetail['key']);
    }

    /**
     * 从字符串中解析秘钥
     * @param string      $key     秘钥
     * @param string|null $keyType 秘钥类型
     *
     * @return false|resource
     * @author JohnsonKing
     */
    private function parseKey(string $key, ?string $keyType = null)
    {
        // 格式化为一行
        if (is_null($keyType)) {
            $keyMultiRows = explode("\n", $key);
            unset($keyMultiRows[0], $keyMultiRows[-1]);
            return implode('', $keyMultiRows);
        }
        // 格式化为多行
        $key = wordwrap($key, 64, "\n", false);
        if ($keyType == 'private') {
            $keyTpl = "-----BEGIN RSA PRIVATE KEY-----\n%s\n-----END RSA PRIVATE KEY-----";
            $binKey = openssl_get_privatekey(sprintf($keyTpl, $key), $this->passphrase);
        } else {
            $keyTpl = "-----BEGIN PUBLIC KEY-----\n%s\n-----END PUBLIC KEY-----";
            $binKey = openssl_get_publickey(sprintf($keyTpl, $key));
        }
        return $binKey;
    }
}