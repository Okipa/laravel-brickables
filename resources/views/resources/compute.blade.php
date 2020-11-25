@push('brickables-css')
    @foreach(Brickables::getCssResourcesToLoad() as $brickableCssResourcePath)
        <link rel="stylesheet" href="{{ $brickableCssResourcePath }}" />
    @endforeach
@endpush

@push('brickables-js')
    @foreach(Brickables::getJsResourcesToLoad() as $brickableJsResourcePath)
        <script src="{{ $brickableJsResourcePath }}"></script>
    @endforeach
@endpush
