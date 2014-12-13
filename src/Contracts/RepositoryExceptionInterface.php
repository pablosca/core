<?php namespace Esensi\Core\Contracts;

use Exception;

/**
 * Repository Exception Interface
 *
 * @author diego <diego@emersonmedia.com>
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface RepositoryExceptionInterface {

    /**
     * Construct the exception
     *
     * @var mixed $bag
     * @var string $message
     * @var integer $code
     * @var Exception $previous
     * @return RepositoryException
     */
    public function __construct($bag, $message = null, $code = 0, Exception $previous = null);

    /**
     * Get the bag property
     *
     * @return Illuminate\Support\Contracts\MessageProviderInterface
     */
    public function getBag();

    /**
     * Get the errors from bag
     *
     * @return array
     */
    public function getErrors();

}