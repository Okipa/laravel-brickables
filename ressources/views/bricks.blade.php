@foreach($model->getBricks() as $brick)
    <div class="my-3">{{ $brick }}</div>
@endforeach
