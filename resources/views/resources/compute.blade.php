@push('brickables-css')
    @foreach(Brickables::getCssResourcesToLoad() as $cssResourcePath)
        <link rel="stylesheet" href="{{ $cssResourcePath }}" />
    @endforeach
@endpush

@push('brickables-js')
    @foreach(Brickables::getJsResourcesToLoad() as $jsResourcePath)
        <script type="text/javascript" src="{{ $jsResourcePath }}"></script>
    @endforeach
@endpush
