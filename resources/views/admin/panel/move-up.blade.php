<form class="mx-1" role="form" method="POST" action="{{ $brick->brickable->getMoveUpRoute($brick) }}">
    @csrf
    <input type="hidden" name="admin_panel_url" value="{{ url()->full() }}#bricks-admin-panel">
    <button class="btn btn-link p-0" type="submit" title="{{ __('Move up') }}">
        <i class="fa-solid fa-arrow-up fa-fw"></i>
    </button>
</form>
