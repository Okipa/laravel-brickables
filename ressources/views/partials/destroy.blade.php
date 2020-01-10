<form class="ml-1" role="form" method="POST" action="{{ $brick->brickable->route('destroy') }}">
    @csrf
    @method('DELETE')
    <button class="btn btn-link p-0 text-danger" type="submit" title="@lang('Destroy')">
        <i class="fas fa-trash fa-fw"></i>
    </button>
</form>
