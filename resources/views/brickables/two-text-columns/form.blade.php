@extends('laravel-brickables::admin.form.layout')
@section('inputs')
    <div class="form-group">
        <label for="left-content">@lang('Left content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="left-content"
                      class="form-control{{ optional($errors ?? null)->has('text_left') ? ' is-invalid' : null }}"
                      name="left_content"
                      placeholder="@lang('Left content')">{{ $brick ? $brick->data['text_left'] : null }}</textarea>
            @if(optional($errors ?? null)->has('text_left'))
                <div class="invalid-feedback">{{ $errors->first('text_left') }}</div>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="right-content">@lang('Right content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="right-content"
                      class="form-control{{ optional($errors ?? null)->has('text_right') ? ' is-invalid' : null }}"
                      name="right_content"
                      placeholder="@lang('Right content')">{{ $brick ? $brick->data['text_right'] : null }}</textarea>
            @if(optional($errors ?? null)->has('text'))
                <div class="invalid-feedback">{{ $errors->first('text') }}</div>
            @endif
        </div>
    </div>
@endsection
