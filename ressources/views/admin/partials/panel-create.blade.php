<form role="form" method="GET" action="{{ route('brick.create') }}">
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <input type="hidden" name="model_type" value="{{ get_class($model) }}">
    <input type="hidden" name="admin_panel_url" value="{{ url()->current() }}">
    <select name="brickable_type">
        <option value="">@lang('Select a brickable')</option>
        @foreach(Brickables::getAll() as $brickable)
            <option value="{{ get_class($brickable) }}">{{ $brickable->getLabel() }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary" type="submit" title="@lang('Create')">
        <i class="fas fa-plus-circle fa-fw"></i> @lang('Add')
    </button>
</form>
