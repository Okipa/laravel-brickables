@extends('laravel-brickables::admin.form.layout')
@section('inputs')
    <div class="form-group">
        <label for="content">{{ __('Content') }}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="content"
                      class="form-control{{ optional($errors ?? null)->has('text') ? ' is-invalid' : null }}"
                      name="content"
                      placeholder="{{ __('Content') }}">{{ optional($brick)->data['text'] }}</textarea>
            @if(optional($errors ?? null)->has('text'))
                <div class="invalid-feedback">{{ $errors->first('text') }}</div>
            @endif
        </div>
    </div>
@endsection
