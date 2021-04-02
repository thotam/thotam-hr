<?php

namespace Thotam\ThotamHr;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thotam\ThotamHr\Skeleton\SkeletonClass
 */
class ThotamHrFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thotam-hr';
    }
}
