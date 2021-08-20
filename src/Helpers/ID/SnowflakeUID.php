<?php


namespace Liushoukun\LaravelHelpers\Helpers\ID;

use Exception;

/**
 * UID 生成
 * Class SnowflakeUID
 */
class SnowflakeUID
{
    // 已秒确定
    public const TWEPOCH = 1621416267000; // 时间起始标记点，作为基准，一般取系统的最近时间（一旦确定不能变动）

    public const WORKER_ID_BITS     = 0; // 机器标识位数
    public const DATACENTER_ID_BITS = 0; // 数据中心标识位数
    public const SEQUENCE_BITS      = 6; // 毫秒内自增位

    public const SEQUENCE_DIVISOR = 1; // 秒 除数 1000 到秒  1  到毫秒


    private $workerId; // 工作机器ID
    private $datacenterId; // 数据中心ID
    private $sequence; // 毫秒内序列

    private $maxWorkerId     = -1 ^ (-1 << self::WORKER_ID_BITS); // 机器ID最大值
    private $maxDatacenterId = -1 ^ (-1 << self::DATACENTER_ID_BITS); // 数据中心ID最大值

    private $workerIdShift      = self::SEQUENCE_BITS; // 机器ID偏左移位数
    private $datacenterIdShift  = self::SEQUENCE_BITS + self::WORKER_ID_BITS; // 数据中心ID左移位数
    private $timestampLeftShift = self::SEQUENCE_BITS + self::WORKER_ID_BITS + self::DATACENTER_ID_BITS; // 时间毫秒左移位数
    private $sequenceMask       = -1 ^ (-1 << self::SEQUENCE_BITS); // 生成序列的掩码

    private $lastTimestamp = -1; // 上次生产id时间戳

    /**
     * @throws Exception
     */
    public function __construct($workerId, $datacenterId, $sequence = 0)
    {
        if ($workerId > $this->maxWorkerId || $workerId < 0) {
            throw new Exception("worker Id can't be greater than {$this->maxWorkerId} or less than 0");
        }

        if ($datacenterId > $this->maxDatacenterId || $datacenterId < 0) {
            throw new Exception("datacenter Id can't be greater than {$this->maxDatacenterId} or less than 0");
        }

        $this->workerId     = $workerId;
        $this->datacenterId = $datacenterId;
        $this->sequence     = $sequence;
    }


    private static $self = null;

    public static function getInstance($workerId, $datacenterId)
    {

        if (self::$self === null) {
            try {
                self::$self = new self($workerId, $datacenterId);
            } catch (Exception $e) {
            }
        }
        return self::$self;
    }


    /**
     * @throws Exception
     */
    public function nextId() : int
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            $diffTimestamp = bcsub($this->lastTimestamp, $timestamp);
            throw new Exception("Clock moved backwards.  Refusing to generate id for {$diffTimestamp} milliseconds");
        }

        if ($this->lastTimestamp == $timestamp) {
            $this->sequence = ($this->sequence + 1) & $this->sequenceMask;

            if (0 === $this->sequence) {
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            // php 不是常驻内存的
            $this->sequence = rand(0, (pow(2, self::SEQUENCE_BITS) - 1));
        }

        $this->lastTimestamp = $timestamp;
        $timestamp           = ((floor($timestamp / self::SEQUENCE_DIVISOR) - floor(self::TWEPOCH / self::SEQUENCE_DIVISOR)));


        return (($timestamp << $this->timestampLeftShift) |
                ($this->datacenterId << $this->datacenterIdShift) |
                ($this->workerId << $this->workerIdShift) |
                $this->sequence);
    }

    protected function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }

        return $timestamp;
    }

    protected function timeGen()
    {
        return floor(microtime(true) * 1000);
    }

}
