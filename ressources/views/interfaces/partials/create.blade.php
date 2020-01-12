<form role="form" method="GET" action="{{ route('brick.create') }}">
    <select name="" id="">
        @foreach(Brickables::all() as $brickable)
            <option value="">{{ $brickable->getLabel() }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary" type="submit" title="@lang('Create')">
        <i class="fas fa-plus-circle fa-fw"></i> @lang('Create')
    </button>
</form>
