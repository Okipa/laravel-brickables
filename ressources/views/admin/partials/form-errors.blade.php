<div class="card text-white bg-danger mb-3">
    <div class="card-header">@lang('Invalid fields have been detected.')</div>
    <div class="card-body">
        <ul>
            @foreach ($errors->all() as $message)
                <li class="card-text">{{ $message }}</li>
            @endforeach
        </ul>
    </div>
</div>
