@if($css)
    @foreach($css as $name)
        <link href="{{$cdn("{$path}/static/css/{$name}.css{$queryString}")}}" rel="stylesheet">
    @endforeach
@endif
@if($div)
    <div id="{{$div}}"></div>
@endif
@if($js)
    @foreach($js as $name)
        <script src="{{$cdn("{$path}/js/{$name}.js{$queryString}")}}"></script>
    @endforeach
@endif
