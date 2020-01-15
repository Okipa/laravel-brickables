<div class="d-flex pt-4">
    <a class="btn btn-danger mr-2" href="{{ $adminPanelUrl }}" role="button">
        <i class="fas fa-ban fa-fw"></i> @lang('Cancel')
    </a>
    <button class="btn btn-primary" type="submit">
        @if($brick)
            <i class="fas fa-save fa-fw"></i> @lang('Update')
        @else
            <i class="fas fa-plus-circle fa-fw"></i> @lang('Create')
        @endif
    </button>
</div>
