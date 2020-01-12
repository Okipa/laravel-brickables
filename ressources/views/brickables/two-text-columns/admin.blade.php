<h1>
    {{ $brickable->getLabel()}} > {{ isset($brick) ? 'Update' : 'Creation' }}
</h1>>
<hr>
<form method="POST" action="{{ isset($brick) ? $brickable->getUpdateRoute($brick) : $brickable->getStoreRoute() }}">
    @csrf
    <div class="form-group">
        <label for="left-content">@lang('Left content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <input id="left-content"
                   class="form-control"
                   type="text"
                   name="left_content"
                   value="{{ isset($brick) ? $brick->content : null }}"
                   placeholder="@lang('Left content')">
        </div>
    </div>
    <div class="form-group">
        <label for="right-content">@lang('Right content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <input id="right-content"
                   class="form-control"
                   type="text"
                   name="right_content"
                   value="{{ isset($brick) ? $brick->content : null }}"
                   placeholder="@lang('Right content')">
        </div>
    </div>
</form>
