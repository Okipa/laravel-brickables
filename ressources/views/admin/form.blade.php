<h1>@lang($model->getReadableClassName()) > @lang($brickable->getLabel()) > @lang($brick ? 'Edition' : 'Creation')</h1>
<hr>
@include('laravel-brickables::admin.partials.validation-errors')
<form method="POST" action="{{ $brick ? $brickable->getUpdateRoute($brick) : $brickable->getStoreRoute() }}">
    @csrf
    @if($brick)@method('PUT')@endif
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <input type="hidden" name="model_type" value="{{ get_class($model) }}">
    <input type="hidden" name="brickable_type" value="{{ get_class($brickable) }}">
    <input type="hidden" name="admin_panel_url" value="{{ $adminPanelUrl }}">
    @yield('inputs')
    @include('laravel-brickables::admin.partials.form-actions')
</form>
