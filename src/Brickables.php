<?php

namespace Okipa\LaravelBrickable;

class Brickables
{
    /**
     * Get available brick types.
     *
     * @return array
     */
    public function getTypes(): array
    {
        return array_map(function ($type) {
            $type['label'] = __($type['label']);

            return $type;
        }, config('brickable.types'));
    }
}
