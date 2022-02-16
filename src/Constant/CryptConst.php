<?php


namespace Htk\Base\Constant;

/**
 * 加解密枚举类
 */
class CryptConst
{
    #---------- 签名算法 ----------#
    /**
     * RSA算法
     */
    const OPENSSL_RSA = 0;

    /**
     * RSA2算法
     */
    const OPENSSL_RSA2 = 1;

    /**
     * AES256-CBC算法
     */
    const OPENSSL_AES256_CBC = 2;


    #---------- RSA公钥类型 ----------#
    /**
     * RSA公钥类型：resource
     */
    const RSA_PUBKEY_RESOURCE = 0;

    /**
     * RSA公钥类型：单行字符串
     */
    const RSA_PUBKEY_ONE_ROW = 1;

    /**
     * RSA公钥类型：多行字符串
     */
    const RSA_PUBKEY_MULTI_ROWS = 2;


    #---------- 整数加密 ----------#
    /**
     * 整数加密最小BIT长度
     */
    const INT_CRYPT_MIN_BIT = 9;

    /**
     * 整数加密分组BIT长度
     */
    const INT_CRYPT_GRP_BIT = 3;
}