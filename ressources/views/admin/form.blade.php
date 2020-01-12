<h1>{{ $brickable->getLabel()}} > {{ $brick ? 'Edition' : 'Creation' }}</h1>
<hr>
<form method="POST" action="{{ $brick ? $brickable->getUpdateRoute($brick) : $brickable->getStoreRoute() }}">
    @csrf
    @if($brick)@method('PUT')@endif
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <input type="hidden" name="model_type" value="{{ $model->get }}">
    @yield('inputs')
    @include('laravel-brickables::admin.partials.form-actions')
</form>
