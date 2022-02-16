<?php


namespace Htk\Base;

/**
 * 常用工具包
 * @package Htk\Base
 */
class HtkUtils
{
    /**
     * 将非驼峰字符串转为驼峰
     * @param string $uncamelizeWords 非驼峰字符串
     * @param string $separator       分隔符
     *
     * @return string
     */
    public static function camelize(string $uncamelizeWords, string $separator = '_'): string
    {
        $uncamelizeWords = $separator . str_replace($separator, ' ', strtolower($uncamelizeWords));

        return ltrim(str_replace(' ', '', ucwords($uncamelizeWords)), $separator);
    }

    /**
     * 驼峰转下划线
     * @param string $camelizeCaps 驼峰字符串
     * @param string $separator    分隔符
     *
     * @return string
     */
    public static function uncamelize(string $camelizeCaps, string $separator = '_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelizeCaps));
    }

    /**
     * 检查指定值是否为空
     * @param mixed $value      待检测值
     * @param bool  $excludeArr 排除数组
     *
     * @return bool
     * @author JohnsonKing
     */
    public static function isEmpty($value, bool $excludeArr = false): bool
    {
        return ($value === '' || $value === null) || (!$excludeArr && (is_array($value) && empty($value)));
    }

    /**
     * 将扁平化数据格式化为树
     * @param  array  $items 待格式化数据
     * @param  string $id    数据ID
     * @param  string $pid   数据父ID
     * @param  string $son   子节点名
     *
     * @return array
     * @author JohnsonKing
     */
    public static function genTree(array $items, string $id = 'id', string $pid = 'pid', string $son = 'children'): array
    {
        // 临时扁平数据
        $tempMap = [];
        foreach ($items as $item) {
            unset($item[$pid]);
            $tempMap[$item[$id]] = $item;
        }
        // 将扁平数据格式化为树
        $tree = [];
        foreach ($items as $item) {
            if (isset($tempMap[$item[$pid]])) {
                $tempMap[$item[$pid]][$son][] = &$tempMap[$item[$id]];
            } else {
                $tempMap[$item[$id]][$son] = $tempMap[$item[$id]][$son] ?? [];
                $tree[] = &$tempMap[$item[$id]];
            }
        }
        // 销毁临时变量，返回结果
        unset($tempMap);
        return $tree;
    }

    /**
     * K-V形式设置
     * @param string $key     键名（支持多级stu.name）
     * @param mixed  $value   键值
     * @param array  $data    目标存储变量
     * @param bool   $isCover 是否覆盖原数据
     *
     * @author JohnsonKing
     */
    public static function setVal(string $key, $value, array &$data, bool $isCover = false)
    {
        $keys = explode('.', $key);
        $count = count($keys) - 1;
        foreach ($keys as $idx => $subkey) {
            // 赋值操作
            if ($subkey == '[]') {
                $data[] = $value;
                break;
            } elseif ($idx === $count) {
                $isMerge = !$isCover && isset($data[$subkey]) && is_array($data[$subkey]) && is_array($value);
                $data[$subkey] = $isMerge ? array_merge($data[$subkey], $value) : $value;
                break;
            }
            // 检查键名是否存在
            if (!key_exists($subkey, $data)) {
                $data[$subkey] = [];
            }
            // 移动游标
            $data = &$data[$subkey];
        }
        return $data;
    }

    /**
     * 将数据格式化为含KEY模式
     * @param array  $dataSources 数据源
     * @param array  $fields      组成KEY的字段
     * @param string $delimiter   KEY分隔符
     * @param bool   $rmKey       是否移除KEY对应值
     *
     * @return array
     * @author JohnsonKing
     */
    public static function toDataWithKey(array $dataSources, array $fields, string $delimiter = '-', bool $rmKey = false): array
    {
        $finalList = [];
        foreach ($dataSources as $dataItem) {
            $keys = [];
            foreach ($fields as  $field) {
                $keys[] = $dataItem[$field] ?? '';
                if ($rmKey) {
                    unset($dataItem[$field]);
                }
            }
            $finalList[implode($delimiter, $keys)] = $dataItem;
        }
        return $finalList;
    }

    /**
     * 存储单位转换
     * @param string $capacity 待转换容量
     * @param string $destUnit 目标单位
     * @param int    $decimal  保留小数
     * @return float
     * @author JohnsonKing
     */
    public static function storageUnitConvert(string $capacity, string $destUnit, int $decimal = 0): float
    {
        // 提取存储单位
        $unitMaps = ['B', 'K', 'M', 'G', 'T'];
        $orginUnit= substr($capacity, -1, 1);
        foreach (['orginUnit', 'destUnit'] as $unitVar) {
            $tmpVal = strtoupper($$unitVar);
            if (!in_array($tmpVal, $unitMaps)) {
                $$unitVar = 'B';
            }
        }
        // 进行单位转换
        $orginUnitSort = array_search(strtoupper($orginUnit), $unitMaps);
        $destUnitSort  = array_search(strtoupper($destUnit), $unitMaps);
        $unitInterval  = $orginUnitSort - $destUnitSort;
        $orginVal = substr($capacity, 0, strlen($capacity)-1);
        $destVal = $orginVal * pow(1024, $unitInterval);
        return number_format($destVal, $decimal, '.', '');
    }
}