@extends('laravel-brickables::layout-test')
@section('content')
    {{ $page->getFirstBrick(get_class($brickableOne)) }}
    {{ Brickables::displayBricks($page, get_class($brickableTwo)) }}
    {{ $page->getBricks(get_class($brickableOne))->last() }}
    @brickablesCompute
@endsection
