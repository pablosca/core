<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of trashable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\TrashableRepositoryInterface
 */
trait TrashableRepositoryTrait{

    /**
     * Read the specified resource from storage even if trashed.
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function retrieve($id)
    {
        // Get the resource
        $object = $this->getModel()
            ->query()
            ->withTrashed()
            ->find($id);

        // Throw an error if resource is not found
        if( ! $object )
        {
            $this->throwException( [], $this->error('retrieve') );
        }

        return $object;
    }

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function trash($id)
    {
        // Retrieve an untrashed resource
        $object = $this->read($id);

        // Soft delete a resource
        // Can't trash a trashed resource
        $result = $object->delete();

        // Throw an error if resource could not be deleted
        if( ! $result )
        {
            $this->throwException( $object->getErrors(), $this->error('trash') );
        }

        return $result;
    }

    /**
     * Restore the specified resource to storage.
     *
     * @todo make restoration rules be part of validation
     * @param integer $id of resource to recover
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function restore($id)
    {
        // Retrieve a trashed resource
        $object = $this->retrieve($id);

        // Restore the object
        $result = $object->restore();

        // Throw an error if resource could not be restored
        if( ! $result )
        {
            $this->throwException( $object->getErrors(), $this->error('restore') );
        }

        return $result;
    }

    /**
     * Remove all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function purge()
    {
        // Force delete all the trashed resources
        $result = $this->getModel()
            ->onlyTrashed()
            ->forceDelete();

        // Throw an error if resources could not be deleted
        if( ! $result )
        {
            $this->throwException( [], $this->error('purge') );
        }

        return $result;
    }

    /**
     * Restore all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function recover()
    {
        // Restore all the trashed resources
        $result = $this->getModel()
            ->onlyTrashed()
            ->restore();

        // Throw an error if resources could not be restored
        if( ! $result )
        {
            $this->throwException( [], $this->error('recover') );
        }

        return $result;
    }

}