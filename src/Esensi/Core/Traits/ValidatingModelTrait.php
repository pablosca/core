<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Models\ValidatingModelObserver;
use \Watson\Validating\ValidatingTrait;

/**
 * Trait that implements the ValidatingModelInterface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ValidatingModelInterface
 */
trait ValidatingModelTrait{

    /**
     * Extend the base trait
     *
     * @see \Watson\Validating\ValidatingTrait
     */
    use ValidatingTrait;

    /**
     * Boot the trait's observers
     *
     * @return void
     */
    public static function bootValidatingTrait()
    {
        static::observe(new ValidatingModelObserver);
    }

}
