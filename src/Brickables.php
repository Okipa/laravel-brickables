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
        return config('brickable.types');
    }

    /**
     * Get available brick types.
     *
     * @param string $brickType
     *
     * @return array
     */
    public function getType(string $brickType): array
    {
        return config('brickable.types.' . $brickType);
    }
}
