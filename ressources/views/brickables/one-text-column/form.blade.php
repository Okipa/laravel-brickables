@extends('laravel-brickables::admin.form')
@section('inputs')
    <div class="form-group">
        <label for="content">@lang('Content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="content" class="form-control" name="content" placeholder="@lang('Content')">
                {{ optional($brick)->content }}
            </textarea>
        </div>
    </div>
@endsection
