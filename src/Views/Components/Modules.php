<?php


namespace Liushoukun\LaravelHelpers\Views\Components;


use Illuminate\Support\Str;
use Illuminate\View\Component;

class Modules extends Component
{


    public string $path;
    public string $div;
    public string|null $queryString = null;

    public array $css = [ 'app', 'chunk-vendors' ];
    public array $js  = [ 'app', 'chunk-vendors' ];


    public function __construct(string $path = '', string $div = 'app',
                                array  $css = [ 'app', 'chunk-vendors' ],
                                array  $js = [ 'app', 'chunk-vendors' ],
    )
    {

        $this->path = $path;
        $this->div  = $div;
        $this->css  = $css;
        $this->js   = $js;

        if (!app()->environment('production')) {
            $this->queryString = '?' . http_build_query([ 'time' => time() ]);
        }

    }

    public function cdn($file)
    {
        $domain = config('app.cdn_domain') ?? config('app.cdn_domain') ?? '';
        $domain = Str::finish($domain, '/');
        $domain = Str::start($domain, '//');
        return $domain . $file;
    }


    /**
     * Get the view / view contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    public function render()
    {
        return view('laravel-helpers::components.modules');
    }


}
