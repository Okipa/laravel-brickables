@foreach($model->getBricks() as $brick)
    <div class="my-4">
        {{ $brick }}
    </div>
@endforeach
