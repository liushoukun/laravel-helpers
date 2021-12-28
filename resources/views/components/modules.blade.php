@if($css)
    @foreach($css as $name)
        <link href="{{$cdn("{$path}/static/css/{$name}.css{$queryString}")}}" rel="stylesheet">
    @endforeach
@endif
<div id="{{$div}}"></div>
@if($js)
    @foreach($js as $name)
        <script src="{{$cdn("{$path}/static/js/{$name}.js{$queryString}")}}"></script>
    @endforeach
@endif
