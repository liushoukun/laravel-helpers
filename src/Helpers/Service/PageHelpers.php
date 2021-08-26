<?php

namespace Liushoukun\LaravelHelpers\Helpers\Service;

class PageHelpers
{

    public static function action()
    {
        return [
            'name'      => '',//名称
            'label'     => '',// 显示
            'disabled'  => false,// 禁用
            'underline' => false, //下划线
            'type'      => 'button',// 操作类型 url button
            'style'     => 'text',// 样式 text button icon
            'href'      => null,// 链接

            'color'           => '#333333',//颜色
            'icon'            => '',// 图标
            'confirm'         => 0,// 是否需要确认 0 | 1
            'confirm_message' => '',// 二次确认提示语
            'tips'            => '',// 提示
            'badge'           => [
                'hidden' => true,
                'value'  => '',
                'max'    => null,
                'is_dot' => false,
                'type'   => null, //primary / success / warning / danger / info
            ],
            'extend'          => [],// 附加参数
        ];
    }

}
