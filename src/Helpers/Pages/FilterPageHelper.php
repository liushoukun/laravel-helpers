<?php
namespace Liushoukun\LaravelHelpers\Helpers\Pages;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class FilterPageHelper implements Arrayable, Jsonable, \JsonSerializable
{


    public const TYPE_SELECT         = 'select'; // 下拉
    public const TYPE_TEXT           = 'text'; // 输入
    public const TYPE_DATETIME       = 'datetime'; // 日期时间
    public const TYPE_TEXT_RANGE     = 'text-range'; // 输入
    public const TYPE_DATETIME_RANGE = 'datetime-range'; // 日期时间范围

    // 表单
    protected $type; //类型
    protected $name; // 字段名称
    protected $title; // 显示标题
    protected $value; // 默认值
    protected $multiple = false; // 是否可以多选
    protected $placeholder; // 占位
    protected $help; // 帮助文字
    protected $options; // 选项
    // 布局
    protected $span;

}
