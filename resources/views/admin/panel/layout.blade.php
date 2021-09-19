<div id="bricks-admin-panel" class="card">
    @foreach(config('brickables.additional_stylesheets.admin_panel', []) as $stylesheet)
        <link href="{{ $stylesheet }}" rel="stylesheet"/>
    @endforeach
    @include('laravel-brickables::admin.panel.title')
    @include('laravel-brickables::admin.panel.content')
</div>
