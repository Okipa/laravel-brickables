@php($additionableBrickables = $model->getAdditionableBrickables())
@if($additionableBrickables->isNotEmpty())
    <form class="d-flex" role="form" method="GET" action="{{ route('brick.create') }}">
        <input type="hidden" name="model_id" value="{{ $model->id }}">
        <input type="hidden" name="model_type" value="{{ get_class($model) }}">
        <input type="hidden" name="admin_panel_url" value="{{ url()->full() }}#bricks-admin-panel">
        <div class="me-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-shapes"></i>
                </span>
                <select class="form-select @error('brickable_type') is-invalid @enderror"
                        name="brickable_type">
                    <option value="" disabled>{{ __('validation.attributes.brickable_type') }}</option>
                    @foreach($additionableBrickables as $brickable)
                        <option value="{{ $brickable::class }}">{{ $brickable->getLabel() }}</option>
                    @endforeach
                </select>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <button class="btn btn-primary" type="submit" title="{{ __('Add') }}">
            <i class="fa-solid fa-circle-plus fa-fw"></i> {{ __('Add') }}
        </button>
    </form>
@endif
