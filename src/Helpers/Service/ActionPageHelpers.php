<?php

namespace Liushoukun\LaravelHelpers\Helpers\Service;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class ActionPageHelpers implements Arrayable, Jsonable, \JsonSerializable
{

    // 操作类型
    public const TYPE_ACTION = 'action'; //
    public const TYPE_URL    = 'url'; //

    // 样式
    public const STYLE_BUTTON = 'button';
    public const STYLE_TEXT   = 'text';
    public const STYLE_ICON   = 'icon';

    // 大小
    public const  SIZE_NORMAL = 'normal';
    public const  SIZE_LARGE  = 'large';
    public const  SIZE_SMALL  = 'small';
    public const  SIZE_MINI   = 'mini';

    // 颜色类型
    public const COLOR_TYPE_DEFAULT = 'default';
    public const COLOR_TYPE_PRIMARY = 'primary';
    public const COLOR_TYPE_INFO    = 'info';
    public const COLOR_TYPE_WARNING = 'warning';
    public const COLOR_TYPE_DANGER  = 'danger';

    protected $name;
    protected $label;
    protected $disabled  = false;
    protected $underline = false;
    protected $type      = self::TYPE_ACTION;
    protected $style     = self::STYLE_BUTTON;
    protected $size      = self::SIZE_NORMAL;
    protected $href      = null;
    protected $color     = null;
    protected $colorType = self::COLOR_TYPE_DEFAULT;
    protected $icon      = null;
    protected $extend    = null;
    protected $tips      = null;


    protected $badge = null;

    protected $countDown = null;
    protected $confirm   = null;

    /**
     * 倒计时
     * @param bool $status
     * @param int $time
     * @param string $format
     * @return $this
     */
    public function countDown(bool $status = true, int $time = 0, string $title = '', string $format = 'mm分ss秒')
    {
        $this->countDown = [
            'title'  => $title,
            'exp'    => now()->addSeconds($time)->toDateTimeString(),
            'status' => $status,
            'time'   => $time,
            'format' => $format,
        ];
        return $this;
    }

    /**
     * 二次确认
     * @param bool $confirm
     */
    public function confirm(bool $confirm = true, string $title = '标题', string $message = null)
    {
        $this->confirm = [
            'confirm' => $confirm,
            'title'   => $title,
            'message' => $message,
        ];
        return $this;
    }

    /**
     * 徽章
     * @param bool $dot
     * @param string $content
     * @param string|null $color
     * @param int $max
     * @return $this
     */
    public function badge(bool $dot = false, $content = '', string $color = null, int $max = 99)
    {
        $this->badge = [
            'content' => $confirm,
            'color'   => $title,
            'dot'     => $message,
            'max'     => $message,
        ];
        return $this;
    }


    /**
     * @param string $size
     * @return $this
     */
    public function size(string $size)
    {
        $this->size = $size;
        return $this;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param string $label
     * @return $this
     */
    public function label(string $label)
    {
        $this->label = $label;
        return $this;
    }


    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled(bool $disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }


    /**
     * @param bool $underline
     * @return $this
     */
    public function underline(bool $underline)
    {
        $this->underline = $underline;
        return $this;
    }


    /**
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $style
     * @return $this
     */
    public function style(string $style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @param string $href
     * @return $this
     */
    public function href(string $href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function color(string $color)
    {
        $this->color = $color;
        return $this;
    }


    /**
     * @param string $colorType
     * @return $this
     */
    public function colorType(string $colorType)
    {
        $this->colorType = $colorType;
        return $this;
    }

    /**
     * @param $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }


    /**
     * @param $tips
     * @return $this
     */
    public function tips($tips)
    {
        $this->tips = $tips;
        return $this;
    }


    /**
     * @param array $extend
     * @return $this
     */
    public function extend(array $extend)
    {
        $this->extend = $extend;
        return $this;
    }


    public function __construct(string $name, string $label = null)
    {
        $this->name  = $name;
        $this->label = $label ?? $name;
    }

    /**
     * 生成链接
     * @param string $name
     * @param string|null $label
     * @return ActionPageHelpers
     */
    public static function url(string $name, string $label = null)
    {
        $self = new self($name, $label);

        $self->type = self::TYPE_URL;

        return $self;
    }

    /**
     * 生成操作
     * @param string $name
     * @param string|null $label
     * @return ActionPageHelpers
     */
    public static function action(string $name, string $label = null)
    {
        $self      = new self($name, $label);
        $self->ype = self::TYPE_URL;
        return $self;
    }

    public function toArray()
    {
        return [
            'name'       => $this->name,//名称
            'label'      => $this->label,// 显示
            'disabled'   => $this->disabled,// 禁用
            'underline'  => $this->underline, //下划线
            'type'       => $this->type,// 操作类型 url action
            'style'      => $this->style,// 样式 text button icon
            'size'       => $this->size,//
            'href'       => $this->href,// 链接
            'color'      => $this->color,//颜色
            'color_type' => $this->colorType,//颜色类型
            'icon'       => $this->icon,// 图标
            'tips'       => $this->tips,// 提示
            'confirm'    => $this->confirm,
            'badge'      => $this->badge,
            'count_down' => $this->countDown,
            'extend'     => $this->extend,// 附加参数
        ];
    }

    public function toJson($options = 0)
    {
        return $this->toArray();
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
