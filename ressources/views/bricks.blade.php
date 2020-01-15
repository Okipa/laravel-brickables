@foreach($model->getBricks() as $brick)
    <div class="my-5">
        {{ $brick }}
    </div>
@endforeach
