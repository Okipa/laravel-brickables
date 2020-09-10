@extends('laravel-brickables::layout-test')
@section('content')
    {{ $page->getFirstBrick(get_class($brickableOne)) }}
    {!! $page->displayBricks([get_class($brickableTwo)]) !!}
    {{ $page->getBricks([get_class($brickableOne)])->last() }}
    @brickableResourcesCompute
@endsection
