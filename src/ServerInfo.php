<?php


namespace Htk\Base;

use Htk\Base\Constant\Constant;

/**
 * 服务器信息
 */
class ServerInfo
{
    // 机器健康级别定义
    private static $machineHealthNames = [
        Constant::HEALTH_LEVEL_RED    => 'red',
        Constant::HEALTH_LEVEL_ORANGE => 'orange',
        Constant::HEALTH_LEVEL_YELLOW => 'yellow',
        Constant::HEALTH_LEVEL_GREEN  => 'green',
    ];

    /**
     * 获取内存信息
     * @return array
     * @author JohnsonKing
     */
    public static function memory(): array
    {
        // 获取内存信息
        exec("free -m|sed -n '2p'|awk '{print $2,$3,$5}'", $diskinfo);
        if (empty($diskinfo)) return [];
        // 内存使用情况
        [$total, $used, $share] = explode(' ', $diskinfo[0]);
        $realUsed = $used + $share;
        return [
            'total' => round($total),
            'used'  => round($realUsed),
            'rate'  => number_format($realUsed / $total, 2),
        ];
    }

    /**
     * 获取进程资源占用信息
     * @param string $threadName 进程名
     * @return array
     * @author JohnsonKing
     */
    public static function thread(string $threadName): array
    {
        // 获取进程信息
        exec("top -n 1|grep '{$threadName}'|grep -v grep|awk '{print $5,$6,$8}'", $threadInfo);
        if (empty($threadInfo)) return [];
        // 分析进程资源占用情况
        [$vsz, $vszRate, $cpuRate] = explode(' ', $threadInfo[0]);
        return [
            'memory'      => HtkUtils::storageUnitConvert($vsz, 'M'),
            'memory_rate' => number_format(str_replace('%', '', $vszRate) / 100, 2),
            'cpu_rate'    => number_format(str_replace('%', '', $cpuRate) / 100, 2),
        ];
    }

    /**
     * 获取磁盘信息
     * @param string $serviceDir 业务目录
     * @return array
     * @author JohnsonKing
     */
    public static function disk(string $serviceDir): array
    {
        // 获取磁盘情况
        exec("df -B 1048576|grep {$serviceDir}|awk '{print $1,$2,$3}'", $diskinfo);
        if (empty($diskinfo)) return [];
        // 分析磁盘使用情况
        [$total, $used, $available] = explode(' ', $diskinfo[0]);
        return [
            'total'     => round($total),
            'used'      => [
                'value' => round($used),
                'rate'  => number_format($used / $total, 2),
            ],
            'available' => [
                'value' => round($available),
                'rate'  => number_format($available / $total, 2),
            ],
        ];
    }

    /**
     * 获取机器负载信息
     * @return array
     * @author JohnsonKing
     */
    public static function loadAverage(): array
    {
        // 获取负载信息和CPU逻辑核数
        exec("top -n 1|grep 'Load average'| grep -v grep|awk '{print $3,$4,$5}'", $loads);
        exec("grep -c 'model name' /proc/cpuinfo", $coreNum);
        if (empty($loads) || empty($coreNum)) return [];
        // 机器负载情况
        $loads = explode(' ', $loads[0]);
        $coreNum = intval($coreNum[0]);
        $loadTypes = ['min1', 'min5', 'min15'];
        foreach ($loads as $idx => $load) {
            $load = number_format($load / $coreNum, 2);
            $level = self::healthCheck($load);
            $loads[$idx] = [
                'type'   => $loadTypes[$idx],
                'load'   => $load,
                'health' => [
                    'level' => $level,
                    'name'  => self::$machineHealthNames[$level],
                ],
            ];
        }
        return $loads;
    }

    /**
     * 检查机器健康状态
     * @param float $loadValue 负载值
     * @return int
     * @author JohnsonKing
     */
    private static function healthCheck(float $loadValue): int
    {
        $healthLevelMaps = [
            Constant::HEALTH_LEVEL_RED    => 5,
            Constant::HEALTH_LEVEL_ORANGE => 1,
            Constant::HEALTH_LEVEL_YELLOW => 0.7,
            Constant::HEALTH_LEVEL_GREEN  => 0,
        ];
        foreach ($healthLevelMaps as $level => $healthScore) {
            if ($loadValue >= $healthScore) {
                return $level;
            }
        }
        return Constant::HEALTH_LEVEL_GREEN;
    }
}