<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: shook Liu  |  Email:24147287@qq.com  | Time:2018/7/31 0:34
// +----------------------------------------------------------------------
// | TITLE: 生成订单号
// +----------------------------------------------------------------------

namespace Liushoukun\LaravelHelpers\Helpers\ID;

/**
 * 商家ID
 * Class SnowflakeOrderID
 * @package App\Helpers\IdGenerator
 */
class SnowflakeOrderID
{

    const debug = 1;

    const SEQUENCE_BITS = 13; // 毫秒内自增位
    public  $sequence = 0;

    static         $timestampLeftShift = 22;
    private static $lastTimestamp      = -1;
    static         $dataCenterIdShift  = 17;
    static         $dataCenterId;
    static         $workerIdShift      = 13;
    public         $sequenceMask       = -1 ^ (-1 << self::SEQUENCE_BITS); // 生成序列的掩码


    /**
     * @var null
     */
    private static $self = NULL;

    /**
     * @return SnowflakeOrderID
     */
    public static function getInstance()
    {

        if (self::$self == NULL) {
            self::$self = new self();
        }
        return self::$self;
    }


    function timeGen()
    {
        //获得当前时间戳
        $time  = explode(' ', microtime());
        $time2 = substr($time[0], 2, 3);
        return $time[1] . $time2;
    }

    private function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

    function nextId()
    {
        $timestamp = $this->timeGen();
        if (self::$lastTimestamp === $timestamp) {
            $this->sequence = ($this->sequence + 1) & $this->sequenceMask;
            if ($this->sequence === 0) {
                $timestamp = $this->tilNextMillis(self::$lastTimestamp);
            }
        } else {
         $this->sequence = rand(0, 1000);
        }
        if ($timestamp < self::$lastTimestamp) {
            throw new \Exception("Clock moved backwards.  Refusing to generate id for " . (self::$lastTimestamp - $timestamp) . " milliseconds");
        }
        self::$lastTimestamp = $timestamp;
        // 共19字符 bigint  64为存储
        //  6        6   1    3     1  1
        // 210524 194500     UID  SID  2
        // 1621858206 323 13 6 2 2 2
        return date('ymdHis', substr($timestamp, 0, 10)) // 12字符
               . substr($timestamp, 10, 3) //;//3字符
               . (string)sprintf("%04d", $this->sequence);//4字符
    }


}
