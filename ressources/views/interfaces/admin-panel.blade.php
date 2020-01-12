@php($bricks = $model->getBricks())
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h2 class="m-0">@lang('Content bricks')</h2>
        <div>@include('laravel-brickables::interfaces.partials.create')</div>
    </div>
    <div class="card-body">
        @if($bricks->isEmpty())
            @include('laravel-brickables::interfaces.partials.empty')
        @else
            @foreach($bricks as $brick)
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="h4 m-0">@lang($brick->brickable->getLabel())</h3>
                        <div class="d-flex">
                            @include('laravel-brickables::interfaces.partials.edit')
                            @include('laravel-brickables::interfaces.partials.destroy')
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $brick }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
