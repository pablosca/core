<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of a findable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\FindableRepositoryInterface
 */
trait FindableRepositoryTrait{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function all()
    {
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return \Esensi\Core\Models\Model
     */
    public function find($id)
    {
        return $this->read($id);
    }

    /**
     * Retrieve the specified resource from trash.
     *
     * @param integer $id of resource
     * @return \Esensi\Core\Models\Model
     */
    public function findInTrash($id)
    {
        return $this->retrieve($id);
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param string $value to match attribute against
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function findBy($attribute, $value)
    {
        // Get the resource
        $object = $this->getModel()
            ->where($attribute, $value)
            ->first();

        // Throw an error if the resource could not be found
        if( ! $object )
        {
            $params = ['attribute' => $attribute, 'value' => $value];
            $message = $this->error('find_by', $params);
            $this->throwException( $message );
        }

        return $object;
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param array $values to match attribute against
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return array
     */
    public function findIn($attribute, array $values = [])
    {
        // Get the resources
        $objects = $this->getModel()
            ->whereIn($attribute, $values)
            ->get();

        // Throw an error if the resource could not be found
        if( ! $objects )
        {
            $params = ['attribute' => $attribute, 'values' => $values];
            $message = $this->error('find_in', $params);
            $this->throwException( $message );
        }

        return $objects;
    }

}