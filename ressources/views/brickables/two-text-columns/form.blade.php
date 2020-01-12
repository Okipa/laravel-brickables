@extends('laravel-brickables::admin.form')
@section('inputs')
    <div class="form-group">
        <label for="left-content">@lang('Left content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="left-content" class="form-control" name="left_content" placeholder="@lang('Left content')">
                {{ optional($brick)->left_content }}
            </textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="right-content">@lang('Right content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <textarea id="right-content" class="form-control" name="right_content" placeholder="@lang('Right content')">
                {{ optional($brick)->right_content }}
            </textarea>
        </div>
    </div>
@endsection
