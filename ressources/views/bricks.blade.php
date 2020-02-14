@foreach($model->getBricks($brickableClass) as $brick)
    <div class="{{ $loop->first ? 'mb-5' : ($loop->last ? 'mt-5' : 'my-5') }}">
        {{ $brick }}
    </div>
@endforeach
