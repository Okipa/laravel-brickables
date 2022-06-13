@extends('laravel-brickables::admin.form.layout')
@section('inputs')
    <div class="mb-3">
        <label class="form-label" for="left-text">{{ __('validation.attributes.text_left') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-font"></i>
            </span>
            <textarea id="left-text"
                      class="form-control @error('text_left') is-invalid @enderror"
                      name="text_left"
                      placeholder="{{ __('validation.attributes.text_left') }}">{{ $brick ? $brick->data['text_left'] : null }}</textarea>
            @error('text_left')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="right-text">{{ __('validation.attributes.text_right') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-font"></i>
            </span>
            <textarea id="right-text"
                      class="form-control @error('text_right') is-invalid @enderror"
                      name="text_right"
                      placeholder="{{ __('validation.attributes.text_right') }}">{{ $brick ? $brick->data['text_right'] : null }}</textarea>
            @error('text_right')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endsection
