<?php namespace Alba\User\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Zizaco\Entrust\EntrustRole;

/**
 * Alba\Role model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\Permission
 * @see Alba\User\Models\User
 */
class Role extends EntrustRole {

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'alpha_dash', 'max:32', 'unique:roles'],
    ];

    /**
     * The attribute rules used by seeder
     * 
     * @var array
     */
    public static $rulesForSeeding = ['name'];

    /**
     * The attribute rules used by store()
     * 
     * @var array
     */
    public static $rulesForStoring = ['name'];

    /**
     * The attribute rules used by update()
     * 
     * @var array
     */
    public static $rulesForUpdating = ['name'];

    /**
     * Rules needed for seeding
     * 
     * @return array
     */    
    public function getRulesForSeedingAttribute()
    {
        return array_only(self::$rules, self::$rulesForSeeding);
    }

    /**
     * Rules needed for storing
     * 
     * @return array
     */    
    public function getRulesForStoringAttribute()
    {
        return array_only(self::$rules, self::$rulesForStoring);
    }

    /**
     * Rules needed for updating
     * 
     * @return array
     */
    public function getRulesForUpdatingAttribute()
    {
        return array_only(self::$rules, self::$rulesForUpdating);
    }

    /**
     * Method added to solve bug:
     *
     * PHP Fatal error:  Class 'Permission' not found in ...../bootstrap/compiled.php
     *
     * 
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     */
    public function perms() {
        // To maintain backwards compatibility we'll catch the exception if the Permission table doesn't exist.
        // TODO remove in a future version
        try {
            return $this->belongsToMany('Alba\User\Models\Permission');
        } catch(Execption $e) {}
    }

    /**
     * Many-to-Many relations with Users
     */
    public function users()
    {
        return $this->belongsToMany('Alba\User\Models\User', 'assigned_roles');
    }

    /**
     * Builds a query scope to return roles alphabetically for a dropdown list
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $column to order by
     * @param string $key to use in returned array
     * @param string $sort direction
     * @return array [$key => $name]
     */
    public function scopeListAlphabetically($query, $column = 'name', $key = 'id', $sort = 'asc')
    {
        return $query->orderBy($column, $sort)->lists($column, $key);
    }

    /**
     * Returns the number of minutes since the creation time
     *
     * @return string
     */
    public function getTimeSinceCreatedAttribute()
    {
        // Short circuit for models that have not been created
        if( is_null($this->created_at) )
        {
            return Lang::get('alba::core.messages.never_created');
        }

        $date = new Carbon($this->created_at);
        return $date->diffForHumans();
    }

    /**
     * Returns the number of minutes since the update time
     *
     * @return string
     */
    public function getTimeSinceUpdatedAttribute()
    {
        // Short circuit for models that have not been updated
        if( is_null($this->updated_at) )
        {
            return Lang::get('alba::core.messages.never_updated');
        }

        $date = new Carbon($this->updated_at);
        return $date->diffForHumans();
    }

    /**
     * Builds a query scope to return roles of a certain permission
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $permissions ids of permission
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfPermission($query, $permissions)
    {
        // Convert permissions string to array
        if ( is_string($permissions) )
            $permissions = explode(',', $permissions);

        // Separate permission names from permission ids
        $permissionNames = [];
        foreach($permissions as $i => $permission)
        {
            if(!is_numeric($permission))
            {
                $permissionNames[] = $permission;
                unset($permissions[$i]);
            }
        }

        // Get permission ids by querying permission names and merge
        $permissionIds = $permissions;
        if(!empty($permissionNames))
        {
            $permissions = Permission::whereIn('name', $permissionNames)->lists('id');
            $permissionIds = array_values(array_unique(array_merge($permissionIds, $permissions), SORT_NUMERIC));
        }

        // Query the permission_role pivot table for matching permissions
        return $query->addSelect(['permission_role.permission_id'])
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('permission_role.permission_id', $permissionIds)
            ->groupBy('roles.id');
    }
}