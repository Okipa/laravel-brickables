@extends('laravel-brickables::admin.form.layout')
@section('inputs')
    <div class="mb-3">
        <label class="form-label" for="text">{{ __('validation.attributes.text') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-font"></i>
            </span>
            <textarea id="text"
                      class="form-control @error('text') is-invalid @enderror"
                      name="text"
                      placeholder="{{ __('validation.attributes.text') }}">{{ $brick ? $brick->data['text'] : null }}</textarea>
            @error('text')
                <div class="invalid-feedback">{{ $message }}</div>
            @endif
        </div>
    </div>
@endsection
