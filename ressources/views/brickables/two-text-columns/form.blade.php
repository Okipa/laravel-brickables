@extends('laravel-brickables::admin.form')
@section('inputs')
    <div class="form-group">
        <label for="left-content">@lang('Left content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="left-content"
                      class="form-control @error('left_content') is-invalid @enderror"
                      name="left_content"
                      placeholder="@lang('Left content')">{{ $brick ? $brick->data['left_content'] : null }}</textarea>
            @error('left_content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <label for="right-content">@lang('Right content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="right-content"
                      class="form-control @error('right_content') is-invalid @enderror"
                      name="right_content"
                      placeholder="@lang('Right content')">{{ $brick ? $brick->data['right_content'] : null }}</textarea>
            @error('right_content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endsection
