<?php

namespace Lukebro\Flash;

use Illuminate\Support\Facades\Facade;

class FlashFacade extends Facade {

    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flash';
    }
}