@php($brick = $brick ?? null)
<h1>{{ $brickable->getLabel()}} > {{ $brick ? 'Edition' : 'Creation' }}</h1>
<hr>
<form method="POST" action="{{ $brick ? $brickable->getUpdateRoute($brick) : $brickable->getStoreRoute() }}">
    @csrf
    @if($brick)@method('PUT')@endif
    @yield('inputs')
    @include('laravel-brickables::admin.partials.form-actions')
</form>
