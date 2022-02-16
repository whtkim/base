<?php


namespace Htk\Base;

use Htk\Base\Constant\CryptConst;

/**
 * 整数加解密工具类
 */
class IntCrypt
{
    /**
     * 键名-最小BIT长度
     */
    const K_MIN_BIT = 'min_bit';

    /**
     * 键名-分组BIT长度
     */
    const K_GRP_BIT = 'grp_bit';

    /**
     * 对整数进行加密
     * @param int $seed     待加密整数
     * @param array $bitOpt 位计算参数：具体值见Constant::class
     *
     * @return int
     * @author JohnsonKing
     */
    public static function make(int $seed, array $bitOpt = []): int
    {
        //  将待加密整数转为二进制
        $minBit = $bitOpt[self::K_MIN_BIT] ?? CryptConst::INT_CRYPT_MIN_BIT;
        $grpBit = $bitOpt[self::K_GRP_BIT] ?? CryptConst::INT_CRYPT_GRP_BIT;
        $sbstr  = str_pad(decbin($seed), $minBit, 0, STR_PAD_LEFT);
        $sblen  = strlen($sbstr);
        [$sbpre, $sbback] = $sblen > $minBit ? [
            substr($sbstr, 0, $sblen - $minBit),
            substr($sbstr, $sblen - $minBit)
        ] : [null, $sbstr];
        // 低N位特殊处理
        $final = [];
        foreach (str_split($sbback, $grpBit) as $key => $val) {
            $randBit = $sblen > $minBit || $key ? rand(0, 1) : 1;
            $final[] = $randBit . $val;
        }
        // 转为十进制返回结果
        return bindec($sbpre.implode($final));
    }

    /**
     * 对已加密整数进行解密
     * @param int   $encodeDec 整数密文
     * @param array $bitOpt    位计算参数：具体值见Constant::class
     *
     * @return int
     * @author JohnsonKing
     */
    public static function parse(int $encodeDec, array $bitOpt = []): int
    {
        // 将密文转为二进制
        $minBit = $bitOpt[self::K_MIN_BIT] ?? CryptConst::INT_CRYPT_MIN_BIT;
        $grpBit = $bitOpt[self::K_GRP_BIT] ?? CryptConst::INT_CRYPT_GRP_BIT;
        $grpNum = $minBit / $grpBit;
        $maxNum = ($grpBit + 1) * $grpNum;
        $edbStr = str_pad(decbin($encodeDec), $maxNum, 0, STR_PAD_LEFT);
        $edbLen = strlen($edbStr);
        [$edbPre, $edbBack] = $edbLen > $maxNum ? [
            substr($edbStr, 0, $edbLen - $maxNum),
            substr($edbStr, $edbLen - $maxNum)
        ] : [null, $edbStr];
        // 低N位特殊处理
        $final = [];
        foreach (str_split($edbBack, $grpBit + 1) as $val) {
            $final[] = substr($val, 1);
        }
        // 转为十进制返回结果
        return bindec($edbPre.implode($final));
    }
}