@foreach($model->getBricks() as $brick)
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>@lang($brick->getLabel())</span>
            <div class="d-flex">
                @include('laravel-brickable::display.partials.edit')
                @include('laravel-brickable::display.partials.destroy')
            </div>
        </div>
        <div class="card-body">
            {{ $brick }}
        </div>
    </div>
@endforeach
