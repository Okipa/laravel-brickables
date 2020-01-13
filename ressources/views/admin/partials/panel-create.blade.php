<form class="form-inline" role="form" method="GET" action="{{ route('brick.create') }}">
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <input type="hidden" name="model_type" value="{{ get_class($model) }}">
    <input type="hidden" name="admin_panel_url" value="{{ url()->current() }}#bricks-admin-panel">
    <div>
        <select class="custom-select mr-3 @error('brickable_type') is-invalid @enderror" name="brickable_type">
            <option value="">@lang('validation.attributes.brickable_type')</option>
            @foreach(Brickables::getAll() as $brickable)
                <option value="{{ get_class($brickable) }}">{{ $brickable->getLabel() }}</option>
            @endforeach
        </select>
        @error('brickable_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button class="btn btn-primary" type="submit" title="@lang('Create')">
        <i class="fas fa-plus-circle fa-fw"></i> @lang('Add')
    </button>
</form>
