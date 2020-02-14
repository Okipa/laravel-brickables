<h1>
    <i class="fas fa-th-large fa-fw"></i>
    {{ $model->getReadableClassName() }} > @lang('Content bricks') > {{ $brickable->getLabel() }} > @lang($brick ? 'Edition' : 'Creation')
</h1>
<hr>
