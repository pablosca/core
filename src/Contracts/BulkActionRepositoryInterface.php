<?php

namespace Esensi\Core\Contracts;

/**
 * Bulk Action Repository Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface BulkActionRepositoryInterface
{
    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string $action to perform
     * @param string|array $ids
     * @return integer count of actions performed
     */
    function bulkAction($action, $ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkDelete($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkRestore($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkTrash($ids);

}
