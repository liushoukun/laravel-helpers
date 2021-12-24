<?php


namespace Liushoukun\LaravelHelpers\Views\Components;


use Dawn\Mall\Auth\Models\Shop;
use Dawn\Mall\Core\Services\Others\ShopService;
use Illuminate\View\Component;

class WindowConfigs extends Component
{

    public $config;
    public $keyName;

    public function __construct( array $config = null,string $keyName = 'config')
    {
        $this->keyName = $keyName;

        if (!isset($config)) {
            $this->config = config('view.window_configs', []);
        } else {
            $this->config = $config;
        }
    }

    public function jsonInt2String($str, $minLength = 16)
    {
        try {
            if (!($str && is_string($str) && $minLength > 0)) {
                return $str;
            }
            // 注意这里可能有想到一种情况就是类似 {"id":111aaa} 的情况，
            // 但是仔细看下，这个111aaa没有用双引号包着，所以并不是正常的json格式，不管这种情况
            return preg_replace('/\":\s*(-?\d{' . $minLength . ',})/', '": "$1"', $str);
        } catch (\Throwable $throwable) {
            return $str;
        }

    }

    /**
     * Get the view / view contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    public function render()
    {

        return view('laraavel-helpers::components.window-config', [ 'config' => $this->config,'keyName'=>$this->keyName]);
    }

}
