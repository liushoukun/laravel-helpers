<script>
    @if($config)
        window.{{$keyName}} = {!! $jsonInt2String(json_encode($config??[[]])) !!};
    @endif
    @if($page_data??[])
        window.page_data = {!! $jsonInt2String(json_encode($page_data??[[]])) !!};
    @endif
</script>
