<h1>
    {{ $brickable->getLabel()}} > {{ isset($brick) ? 'Update' : 'Creation' }}
</h1>>
<hr>
<form method="POST" action="{{ isset($brick) ? $brickable->getUpdateRoute($brick) : $brickable->getStoreRoute() }}">
    @csrf
    <div class="form-group">
        <label for="text-zip-code">@lang('Content')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-font"></i></span>
            </div>
            <input class="form-control"
                   type="text"
                   name="content"
                   value="{{ isset($brick) ? $brick->content : null }}"
                   placeholder="@lang('Content')">
        </div>
    </div>
    <div class="d-flex pt-4">
        <div class="component-container mr-2">
            <a href="http://starter.test/admin/actualites/articles"
               class="component btn btn-danger load-on-click"
               title="Annuler">
                <span class="label-prepend"><i class="fas fa-ban fa-fw"></i></span> <span class="label">Annuler</span>
            </a>
        </div>
        <div class="component-container form-group">
            <button type="submit" class="component form-control btn btn-primary load-on-click" title="Mettre à jour">
                <span class="label-prepend"><i class="fas fa-save fa-fw"></i></span> <span class="label">Mettre à
                                                                                                         jour</span>
            </button>
        </div>
    </div>
</form>
