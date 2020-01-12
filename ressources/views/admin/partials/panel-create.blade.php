<form role="form" method="GET" action="{{ route('brick.create') }}">
    <select name="" id="">
        @foreach(Brickables::getAll() as $brickable)
            <option value="{{ get_class($brickable) }}">{{ $brickable->getLabel() }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary" type="submit" title="@lang('Create')">
        <i class="fas fa-plus-circle fa-fw"></i> @lang('Add')
    </button>
</form>
