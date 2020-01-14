@extends('laravel-brickables::admin.form')
@section('inputs')
    <div class="form-group">
        <label for="content">@lang('Content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="content"
                      class="form-control{{ optional($errors ?? null)->has('content') ? ' is-invalid' : null }}"
                      name="content"
                      placeholder="@lang('Content')">{{ $brick ? $brick->data['content'] : null }}</textarea>
            @if(optional($errors ?? null)->has('content'))
                <div class="invalid-feedback">{{ $errors->first('content') }}</div>
            @endif
        </div>
    </div>
@endsection
