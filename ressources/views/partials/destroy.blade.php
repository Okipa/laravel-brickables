<form class="ml-1" role="form" method="POST" action="">
    @csrf
    @method('DELETE')
    <button class="btn btn-link p-0 text-danger" type="submit" title="@lang('laravel-brickables::laravel-brickables.destroy')">
        <i class="fas fa-trash fa-fw"></i>
    </button>
</form>
