<div class="d-flex">
    <a class="btn btn-danger mr-2" href="{{ url()->previous() }}" role="button">
        <i class="fas fa-ban fa-fw"></i> @lang('Cancel')
    </a>
    <button type="submit" class="btn btn-primary">
        @if($brick)
            <i class="fas fa-save fa-fw"></i> @lang('Update')
        @else
            <i class="fas fa-plus-circle fa-fw"></i> @lang('Create')
        @endif
    </button>
</div>
