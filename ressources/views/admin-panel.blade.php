<div class="card mt-3">
    <div class="card-header">
        <h2 class="m-0">
            @lang('laravel-brickables::laravel-brickables.contentBricks')
        </h2>
    </div>
    <div class="card-body">
        @foreach($model->getBricks() as $brick)
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span>@lang($brick->getLabel())</span>
                    <div class="d-flex">
                        @include('laravel-brickables::partials.edit')
                        @include('laravel-brickables::partials.destroy')
                    </div>
                </div>
                <div class="card-body">
                    {{ $brick }}
                </div>
            </div>
        @endforeach
    </div>
</div>
