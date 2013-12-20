<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;

/**
 * Controller for accessing RolesResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\RolesResource
 */
class RolesApiController extends Controller {

    /**
     * Inject dependencies
     *
     * @param RolesResource $rolesResource;
     * @return void
     */
	public function __construct(\AlbaRolesResource $rolesResource)
	{
		$this->resources['role'] = $rolesResource;
	}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keywords');

        // Filter by permission
        if( $permissions = Input::get('permissions', false) )
        {
            $permissions = is_array($permissions) ? $permissions : explode(',', $permissions);
            $permissions = array_values($permissions);
            $test = implode('', $permissions);
            if(!empty($test))
            {
                $params['permissions'] = $permissions;
                $params['scopes']['ofPermission'] = [ $permissions ];
            }
        }

        return $this->resources['role']->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $attributes = Input::all();
        return $this->resources['role']->store($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->resources['role']->show($id);
    }

    /**
     * Display the specified resource by name.
     *
     * @param string $name of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showByName($name)
    {
        return $this->resources['role']->showByName($name);
    }

    /**
     * Display the specified resource with users.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showUsers($id)
    {
        $object = $this->resources['role']->show($id);
        $object->load('users');
        return $object;
    }

    /**
     * Display the specified resource with permissions.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showPermissions($id)
    {
        $object = $this->resources['role']->show($id);
        $object->load('perms');
        return $object;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        $attributes = Input::all();
        $object = $this->resources['role']->update($id, $attributes);
        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id of object to remove
     * @param bool $force delete
     * @return bool
     * 
     */
    public function destroy($id)
    {
        return $this->resources['role']->destroy($id);
    }

    /**
     * Update the permissions attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function assignPermissions($id)
    {  
        // Convert permissions to array
        $permissions = Input::get('permissions', []);
        if(is_string($permissions))
            $permissions = explode(',', $permissions);

        // Sync permissions
        $object = $this->resources['role']->syncPermissions($id, $permissions);
        return $object;
    }

    /**
     * Display a list of all roles
     *
     * @return array
     */
    public function names()
    {
        return $this->resources['role']->names();
    }

}